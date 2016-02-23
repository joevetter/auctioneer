<?php
namespace Auctioneer\Controllers;

use Auctioneer\General\Validator;
use Auctioneer\General\LoggedIn;

class BaseController
{

  protected $loader;
  protected $twig;

  public function __construct()
  {
    $this->loader = new \Twig_Loader_Filesystem(__DIR__ . "/../views");
    $this->twig = new \Twig_Environment($this->loader,[
        'cache' => false,
        'debug' => true,
    ]);
  }

}
