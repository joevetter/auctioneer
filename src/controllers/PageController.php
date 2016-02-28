<?php
namespace Auctioneer\Controllers;

use Auctioneer\Models\User;
use Auctioneer\Models\Page;
use Auctioneer\General\LoggedIn;
use Auctioneer\Models\Auction;
use Auctioneer\Controllers\AuctionController;

class PageController extends BaseController
{

  public function getShowHomePage()
  {
    echo $this->twig->render('home.html',[
      'session' => LoggedIn::user()]);
  }

  public function getShowPage()
  {
    $title = '';
    $content = '';

    # extract page name from url
    $uri = explode("/", $_SERVER['REQUEST_URI']);
    $target = $uri[1];
    # find matching page in the db
    $page = Page::where('slug', '=', $target)->get();
    # look up page content
    foreach($page as $row)
    {
      $title = $row->title;
      $content = $row->content;
    }

    if(empty($title))
    {
      header("HTTP/1.0 404 Not Found");
      header("Location: /notFound");
      exit();
    }
    # pass content to twig te
    echo $this->twig->render('genericPage.html',[
      'session' => LoggedIn::user(),
      'title'   => $title,
      'content' => $content]);
  }

  public function getShowNotFound()
  {
    echo $this->twig->render('notFound.html', [
      'session' => LoggedIn::user(),
      ]);
  }

  public function getShowTest()
  {
    #
  }

  public function postShowTest()
  {
    #
  }

}
