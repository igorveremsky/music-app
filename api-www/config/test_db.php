<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=dbtest;dbname=gt_music_app_test_db';

return $db;
