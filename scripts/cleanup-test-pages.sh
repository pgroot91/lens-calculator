#!/bin/bash
set -e

echo "🧹 Cleaning up test pages and menu..."

# Delete pages if they exist
for title in "Page One" "Page Two" "Page Three"; do
  IDS=$(wp post list --post_type=page --title="$title" --format=ids)
  if [ -n "$IDS" ]; then
    wp post delete $IDS --force
    echo "✅ Deleted page(s) with title: $title (IDs: $IDS)"
  else
    echo "ℹ️ No page found with title: $title"
  fi
done

# Remove menu if it exists
if wp menu list --format=csv | grep -q "Main Menu"; then
  wp menu delete "Main Menu" || true
  echo "✅ Deleted Main Menu"
else
  echo "ℹ️ No Main Menu found"
fi

echo "✨ Cleanup complete"
