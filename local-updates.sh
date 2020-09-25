#!/bin/bash
####################################
#
# Update Local Server
#
####################################

#Live Directory Path
#path=/home/digima/live

#Commands
echo "Updating..."
echo
#cd $path
git reset --hard origin/client_inventory
#composer install --profile --prefer-dist -vvv --no-progress
php artisan cache:clear
php artisan optimize
php artisan route:cache
php artisan view:clear
php artisan config:cache
composer dump-autoload
php artisan migrate --force
#chmod -R 777 $path
echo
echo "Finished!"

