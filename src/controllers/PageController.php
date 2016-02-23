<?php
namespace Auctioneer\Controllers;

use Auctioneer\Models\User;
use Auctioneer\Models\Page;
use Auctioneer\General\LoggedIn;
use Auctioneer\Models\Auction;

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
      header("Location: /page-not-found");
      exit();
    }
    # pass content to twig te
    echo $this->twig->render('genericPage.html',[
      'session' => LoggedIn::user(),
      'title'   => $title,
      'content' => $content]);
  }

  public function getShowTest()
  {
    $username = LoggedIn::user()->username;
    var_dump($username);

    $bids = Auction::join('users AS users_seller', 'auctions.seller_id', '=', 'users_seller.id')
      ->leftJoin('users AS users_buyer', 'auctions.buyer_id', '=', 'users_buyer.id')
      ->select('auctions.*', 'users_seller.username AS sellername', 'users_buyer.username AS buyername')
      ->where('auctions.seller_id', '=', LoggedIn::user()->id)->get();
    var_dump($bids);
    print_r($bids[0]);
  }

}
