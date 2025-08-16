#!/bin/bash
set -e

# Environments to run against
ENVIRONMENTS=("cli" "tests-cli")

for ENV in "${ENVIRONMENTS[@]}"; do
  echo "🔹 Running setup in environment: $ENV"
  WP="npx wp-env run $ENV wp"

  echo "🛠️ Setting up WordPress test data in $ENV..."

  # Create pages
  ID1=$($WP post create --post_type=page --post_title="Page One" --post_status=publish --post_content="[shortcode_one]" --porcelain)
  echo "✅ Created Page One (ID: $ID1)"

  ID2=$($WP post create --post_type=page --post_title="Page Two" --post_status=publish --post_content="[shortcode_two]" --porcelain)
  echo "✅ Created Page Two (ID: $ID2)"

  ID3=$($WP post create --post_type=page --post_title="Page Three" --post_status=publish --post_content="[shortcode_three]" --porcelain)
  echo "✅ Created Page Three (ID: $ID3)"

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
      {\"blockName\":\"core/navigation-link\",\"attrs\":{\"label\":\"Page One\",\"url\":\"$( $WP post get $ID1 --field=guid )\"},\"innerBlocks\":[]},
      {\"blockName\":\"core/navigation-link\",\"attrs\":{\"label\":\"Page Two\",\"url\":\"$( $WP post get $ID2 --field=guid )\"},\"innerBlocks\":[]},
      {\"blockName\":\"core/navigation-link\",\"attrs\":{\"label\":\"Page Three\",\"url\":\"$( $WP post get $ID3 --field=guid )\"},\"innerBlocks\":[]}
    ]
  }"

  # Insert navigation block
  $WP post meta update $HEADER_ID _wp_block_content "$NAV_JSON"
  echo "📌 Added navigation block with pages to header template"

  echo "✨ Setup complete in $ENV. Pages: $ID1, $ID2, $ID3"
done
