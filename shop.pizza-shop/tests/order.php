<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\dto\order\ItemDTO;
use pizzashop\shop\domain\entities\catalogue\Category;
use pizzashop\shop\domain\entities\catalogue\Size;
use pizzashop\shop\domain\entities\catalogue\Product;
use Faker\Factory;
use pizzashop\shop\domain\entities\commande\Commande;

$dbcom = __DIR__ . '/../config/commande.db.ini';
$dbcat = __DIR__ . '/../config/catalog.db.ini';
$db = new DB();
$db->addConnection(parse_ini_file($dbcom), 'commande');
$db->addConnection(parse_ini_file($dbcat), 'catalog');
$db->setAsGlobal();
$db->bootEloquent();


