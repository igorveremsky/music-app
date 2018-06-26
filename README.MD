# Music Application

## Links

**API** - http://api.gt-music-app.com:8080/<br>
**Admin panel** - http://admin.gt-music-app.com:8080/<br>

## Versions

**Docker** - 0.9.0<br>
**API** - 0.4.0<br>
**Admin panel** - 0.0.0<br>
**Nginx** - 0.1.0<br>
**PHPUnit Tests** - 0.0.0<br>
**DB** - 0.4.1<br>

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
- [ ] test dumpy data import script
- [ ] admin panel application setup configuration
- [ ] ElasticSearch
- [ ] .env
- [ ] entrypoint.sh

### Nginx

- [x] api application configurations
- [ ] admin-panel application configurations

### API

- [x] General api server configurations
- [x] Authorization
- [ ] Artists CRUD
- [x] Albums CRUD
- [ ] Tracks CRUD
- [x] Genres CRUD
- [ ] Favorites CRUD (tracks, albums or artists)
- [ ] test dumpy data
- [ ] code refactoring and optimization

### Admin Panel

- [ ] General configurations
- [ ] packages
- [ ] RBAC (all actions accessible only for login user)
- [ ] Artists CRUD
- [ ] Albums CRUD
- [ ] Tracks CRUD
- [ ] Genres CRUD
- [ ] Favorites CRUD (tracks, albums or artists)
- [ ] Make favorite track, album or artist action
- [ ] code refactoring and optimization

### DB

- [ ] user authorization structure
- [x] artists structure
- [x] albums structure
- [x] tracks structure
- [x] genres structure
- [x] favorites structure
- [ ] elasticsearch
- [ ] structure optimization (add indexes etc)

### PHPUnit Tests

- [ ] admin panel access
- [ ] artists cud
- [ ] albums cud
- [ ] tracks cud
- [ ] genres cud
- [ ] favorites cud

### General

- [ ] installation and build guide
- [ ] configuration docs