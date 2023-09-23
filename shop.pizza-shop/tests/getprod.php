<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;
use pizzashop\shop\domain\entities\catalogue\Category;
use pizzashop\shop\domain\entities\catalogue\Size;
use pizzashop\shop\domain\entities\catalogue\Product;

$dbconf = __DIR__ . '/../config/catalog.db.ini';
$db = new DB();
$db->addConnection(parse_ini_file($dbconf), 'catalog');
$db->setAsGlobal();
$db->bootEloquent();

