#!/bin/bash
set -e

# Environments to run against
ENVIRONMENTS=("tests-cli")

for ENV in "${ENVIRONMENTS[@]}"; do
  echo "🔹 Running setup in environment: $ENV"
  WP="npx wp-env run $ENV wp"

  echo "🛠️ Setting up WordPress test data in $ENV..."

  # Create pages
  ID1=$($WP post create --post_type=page --post_title="Full Calculator" --post_status=publish --post_content="[full-calculator]" --porcelain)
  echo "✅ Created Full Calculator page and hid the title (ID: $ID1)"

  ID2=$($WP post create --post_type=page --post_title="Width Calculator" --post_status=publish --post_content="[width-calculator]" --porcelain)
  echo "✅ Created Width Calculator page and hid the title (ID: $ID2)"

  ID3=$($WP post create --post_type=page --post_title="Height Calculator" --post_status=publish --post_content="[height-calculator]" --porcelain)
  echo "✅ Created Height Calculator page and hid the title (ID: $ID3)"

  # Set Full Calculator as homepage
  $WP option update show_on_front page
  $WP option update page_on_front $ID1
  echo "🏠 Set Full Calculator as homepage"

  # Check or create header template
  HEADER_ID=$($WP post list --post_type=wp_template_part --field=ID --name=header 2>/dev/null || true)
  if [ -z "$HEADER_ID" ]; then
    HEADER_ID=$($WP post create --post_type=wp_template_part --post_title="Header" --post_name="header" --post_status=publish --porcelain)
    echo "✅ Created header template part (ID: $HEADER_ID)"
  fi

  # Create navigation block JSON
  NAV_JSON="{
    \"blockName\":\"core/navigation\",
    \"attrs\":{},
    \"innerBlocks\":[
      {\"blockName\":\"core/navigation-link\",\"attrs\":{\"label\":\"Full Calculator\",\"url\":\"$( $WP post get $ID1 --field=guid )\"},\"innerBlocks\":[]},
      {\"blockName\":\"core/navigation-link\",\"attrs\":{\"label\":\"Width Calculator\",\"url\":\"$( $WP post get $ID2 --field=guid )\"},\"innerBlocks\":[]},
      {\"blockName\":\"core/navigation-link\",\"attrs\":{\"label\":\"Height Calculator\",\"url\":\"$( $WP post get $ID3 --field=guid )\"},\"innerBlocks\":[]}
    ]
  }"

  # Insert navigation block
  $WP post meta update $HEADER_ID _wp_block_content "$NAV_JSON"
  echo "📌 Added navigation block with pages to header template"

  echo "✨ Setup complete in $ENV. Pages: $ID1, $ID2, $ID3"
done
