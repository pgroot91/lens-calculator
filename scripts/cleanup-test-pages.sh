#!/bin/bash
set -e

# Path to WP-CLI (adjust this to match your system, e.g. /usr/local/bin/wp or vendor/bin/wp)
WP="php ./vendor/wp-cli/wp-cli/bin/wp"

echo "🧹 Cleaning up test pages and menu..."

# Delete pages if they exist
for title in "Page One" "Page Two" "Page Three"; do
  IDS=$($WP post list --post_type=page --title="$title" --format=ids)
  if [ -n "$IDS" ]; then
    $WP post delete $IDS --force
    echo "✅ Deleted page(s) with title: $title (IDs: $IDS)"
  else
    echo "ℹ️ No page found with title: $title"
  fi
done

# Remove menu if it exists
if $WP menu list --format=csv | grep -q "Main Menu"; then
  $WP menu delete "Main Menu" || true
  echo "✅ Deleted Main Menu"
else
  echo "ℹ️ No Main Menu found"
fi

echo "✨ Cleanup complete"
