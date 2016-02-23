<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
  'driver'      => getenv('DB_DRIVER'),
  'host'        => getenv('DB_HOST'),
  'database'    => getenv('DB_DATABASE'),
  'username'    => getenv('DB_USER'),
  'password'    => getenv('DB_PASS'),
  'charset'     => getenv('DB_CHARSET'),
  'collation'   => getenv('DB_COLLATION'),
  'prefix'      => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
