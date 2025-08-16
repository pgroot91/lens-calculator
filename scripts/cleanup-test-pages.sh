#!/bin/bash
set -e

# Environments to clean up
ENVIRONMENTS=("tests-cli")

for ENV in "${ENVIRONMENTS[@]}"; do
  echo "🔹 Cleaning up in environment: $ENV"
  WP="npx wp-env run $ENV wp"

  echo "🧹 Cleaning up test pages and menu in $ENV..."

  # Delete pages if they exist
  for title in "Full Calculator" "Width Calculator" "Height Calculator" "Sample Page"; do
    IDS=$($WP post list --post_type=page --title="$title" --format=ids)
    if [ -n "$IDS" ]; then
      $WP post delete $IDS --force
      echo "✅ Deleted page(s) with title: $title (IDs: $IDS)"
    else
      echo "ℹ️ No page found with title: $title"
    fi
  done

  # Remove menu if it exists
  MENU_NAME="Main Menu"
  if $WP menu list --format=csv | grep -q "$MENU_NAME"; then
    # Remove menu items first (safe for block-based nav)
    ITEM_IDS=$($WP menu item list "$MENU_NAME" --format=ids)
    if [ -n "$ITEM_IDS" ]; then
      $WP menu item delete $ITEM_IDS --force
      echo "🧹 Removed menu items from $MENU_NAME (IDs: $ITEM_IDS)"
    fi

    $WP menu delete "$MENU_NAME" || true
    echo "✅ Deleted $MENU_NAME"
  else
    echo "ℹ️ No $MENU_NAME found"
  fi

  echo "✨ Cleanup complete in $ENV"
done
