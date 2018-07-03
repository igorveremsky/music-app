# Music Application

## Installation Guide

1. Change line separator at ```docker/php/configs/composer```, ```docker/php/configs/docker-php-entrypoint``` to ```LF (\n)```
2. Add 
```
127.0.0.1 api.gt-music-app.com
127.0.0.1 admin.gt-music-app.com
```
to ```hosts``` config
3. Run ```entrypoint.sh```
4. Enjoy the silence :)

## Links

**API** - http://api.gt-music-app.com/<br>
**Admin panel** - http://admin.gt-music-app.com/<br>
**phpMyAdmin** - http://localhost:8081/<br>
**kibana** - http://localhost:5601/<br>

## Demo Users

1. Login: ```admin```, Password ```admin```
2. Login: ```demo```, Password ```demo```

## Tests

Run ```docker exec -w /var/www/html/api.gt-music-app.com app-php ./vendor/bin/codecept run unit```

## Versions

**Docker** - 0.10.1<br>
**API** - 0.8.1<br>
**Admin panel** - 0.9.0<br>
**Nginx** - 0.2.0<br>
**PHPUnit Tests** - 0.5.1<br>
**DB** - 0.4.2<br>

## TODO

### Docker

- [x] PHP 7.2-fpm
- [x] PHP Extensions for Yii2
- [x] Composer
- [x] init api application source files
- [x] init admin-panel application source files
- [x] nginx (1.15)
- [x] MySQL (8.0.3, InnoDB)
- [x] PhpMyAdmin (4.7)
- [x] server configurations (nginx)
- [ ] api application setup configuration
- [x] test dumpy data import script
- [ ] admin panel application setup configuration
- [x] ElasticSearch
- [ ] .env
- [x] entrypoint.sh

### Nginx

- [x] api application configurations
- [x] admin-panel application configurations

### API

- [x] General api server configurations
- [x] Authorization
- [x] Artists CRUD
- [x] Albums CRUD
- [x] Tracks CRUD
- [x] Genres CRUD
- [x] Favorites CRUD (tracks, albums or artists)
- [x] test dumpy data
- [ ] code refactoring and optimization

### Admin Panel

- [x] General configurations
- [x] packages
- [x] RBAC (all actions accessible only for login user)
- [x] Artists CRUD
- [x] Albums CRUD
- [x] Tracks CRUD
- [x] Genres CRUD
- [x] Favorites CRUD (tracks, albums or artists)
- [x] Make favorite track, album or artist action
- [ ] code refactoring and optimization

### DB

- [ ] user authorization structure
- [x] artists structure
- [x] albums structure
- [x] tracks structure
- [x] genres structure
- [x] favorites structure
- [x] elasticsearch
- [ ] structure optimization (add indexes etc)

### PHPUnit Tests

- [ ] admin panel access
- [x] artists cud
- [x] albums cud
- [x] tracks cud
- [x] genres cud
- [x] favorites cud

### General

- [x] installation and build guide
- [ ] configuration docs