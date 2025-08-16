#!/bin/bash
set -e

# Path to WP-CLI (adjust this to match your system, e.g. /usr/local/bin/wp or vendor/bin/wp)
WP="npm run --silent env run cli wp"

echo "🛠️ Setting up WordPress test data..."

# Create pages
ID1=$($WP post create --post_type=page --post_title="Page One" --post_status=publish --post_content="[shortcode_one]" --porcelain)
echo "✅ Created Page One (ID: $ID1)"

ID2=$($WP post create --post_type=page --post_title="Page Two" --post_status=publish --post_content="[shortcode_two]" --porcelain)
echo "✅ Created Page Two (ID: $ID2)"

ID3=$($WP post create --post_type=page --post_title="Page Three" --post_status=publish --post_content="[shortcode_three]" --porcelain)
echo "✅ Created Page Three (ID: $ID3)"

# Create menu if not exists
if $WP menu list --format=csv | grep -q "Main Menu"; then
  echo "ℹ️ Main Menu already exists"
else
  $WP menu create "Main Menu"
  echo "✅ Created Main Menu"
fi

# Add pages to menu
$WP menu item add-post "main-menu" $ID1 >/dev/null
echo "📌 Added Page One to Main Menu"

$WP menu item add-post "main-menu" $ID2 >/dev/null
echo "📌 Added Page Two to Main Menu"

$WP menu item add-post "main-menu" $ID3 >/dev/null
echo "📌 Added Page Three to Main Menu"

# Assign menu to primary location
$WP menu location assign "main-menu" primary
echo "✅ Assigned Main Menu to primary location"

echo "✨ Setup complete. Pages created: $ID1, $ID2, $ID3"
