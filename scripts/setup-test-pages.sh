#!/bin/bash
set -e

echo "🛠️ Setting up WordPress test data..."

# Create pages
ID1=$(wp post create --post_type=page --post_title="Page One" --post_status=publish --post_content="[shortcode_one]" --porcelain)
echo "✅ Created Page One (ID: $ID1)"

ID2=$(wp post create --post_type=page --post_title="Page Two" --post_status=publish --post_content="[shortcode_two]" --porcelain)
echo "✅ Created Page Two (ID: $ID2)"

ID3=$(wp post create --post_type=page --post_title="Page Three" --post_status=publish --post_content="[shortcode_three]" --porcelain)
echo "✅ Created Page Three (ID: $ID3)"

# Create menu if not exists
if wp menu list --format=csv | grep -q "Main Menu"; then
  echo "ℹ️ Main Menu already exists"
else
  wp menu create "Main Menu"
  echo "✅ Created Main Menu"
fi

# Add pages to menu
wp menu item add-post "main-menu" $ID1 >/dev/null
echo "📌 Added Page One to Main Menu"

wp menu item add-post "main-menu" $ID2 >/dev/null
echo "📌 Added Page Two to Main Menu"

wp menu item add-post "main-menu" $ID3 >/dev/null
echo "📌 Added Page Three to Main Menu"

# Assign menu to primary location
wp menu location assign "main-menu" primary
echo "✅ Assigned Main Menu to primary location"

echo "✨ Setup complete. Pages created: $ID1, $ID2, $ID3"
