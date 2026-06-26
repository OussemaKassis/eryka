#!/usr/bin/env bash

echo "Linking storage..."
php artisan storage:link

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Seeding admin user..."
php artisan db:seed --force
