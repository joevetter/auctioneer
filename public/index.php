<?php

$controller = null;
$method = null;

require_once(__DIR__ . "/../bootstrap/start.php");
$dotenv = new Dotenv\Dotenv(__DIR__ . "/../bootstrap/");
$dotenv->load();
require_once(__DIR__ . "/../bootstrap/db.php");
require_once(__DIR__ . "/../bootstrap/routes.php");

$match = $router->match();

if(is_string($match['target']))
{
  # routes calling methods of specific controller
  list($controller, $method) = explode("@", $match['target']);
}
/*
$object = new PageController();
call_user_func_array(array($object, 'getShowPage'), array());
exit();
*/
if($controller != null && is_callable(array($controller, $method)))
{
  $object = new $controller();
  call_user_func_array(array($object, $method), array($match['params']));
} elseif ($match && is_callable($match['target'])) {
  call_user_func_array($match['target'], $match['params']);
} else {
  echo "Cannot find $controller -> $method";
  exit();
}
