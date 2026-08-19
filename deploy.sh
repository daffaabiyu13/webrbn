#!/bin/bash
# Deploy script — dijalankan otomatis dari GitHub Actions setelah push.
# Bisa juga dijalankan manual via SSH: `./deploy.sh`

set -e  # stop kalau ada command yang gagal

echo "▸ Pull latest dari GitHub..."
git fetch origin
git reset --hard origin/claude/stoic-franklin-VT0ru

echo "▸ Cek migrasi baru..."
php artisan migrate --force

echo "▸ Refresh cache config, route, view..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "▸ Storage symlink..."
php artisan storage:link 2>/dev/null || true

echo "✓ Deploy selesai — $(date +'%Y-%m-%d %H:%M:%S')"
