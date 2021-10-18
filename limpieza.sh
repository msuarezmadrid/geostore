#!/usr/bin/bash
echo "INICIO LIMPIEZA"
php artisan cache:clear
php artisan config:cache
php artisan config:clear
php artisan view:clear
composer dump-autoload
echo "FIN LIMPIEZA"
