#!/usr/bin/env bash

docker-compose stop;
docker-compose up -d --build;
docker exec -w "//var/www/html/api.gt-music-app.com" app-php composer install;
docker exec -w "//var/www/html/api.gt-music-app.com" app-php php yii migrate --interactive=0;
docker exec -w "//var/www/html/api.gt-music-app.com" app-php php ./tests/bin/yii migrate --interactive=0;
docker exec -w "//var/www/html/api.gt-music-app.com" app-php php yii demo-content/import;
docker exec -w "//var/www/html/api.gt-music-app.com" app-php php yii elastic-content/index-init;
docker exec -w "//var/www/html/api.gt-music-app.com" app-php php yii elastic-content/import-from-db;
docker exec -w "//var/www/html/admin.gt-music-app.com" app-php composer install;
read -p "Press any key to continue... " -n1 -s