<?php
namespace Auctioneer\Controllers;

use Auctioneer\General\LoggedIn;
use Auctioneer\General\Validator;
use Auctioneer\General\Beautify;
use Auctioneer\Models\Auction;
use Auctioneer\Models\Image;

class AuctionController extends BaseController
{

  public function getShowMyAuctions()
  {
    $bids = [];
    $sales = [];

    $bids = Auction::join('users AS users_buyer', 'auctions.buyer_id', '=', 'users_buyer.id')
      ->leftJoin('users AS users_seller', 'auctions.seller_id', '=', 'users_seller.id')
      ->select('auctions.*', 'users_seller.username AS sellername', 'users_buyer.username AS buyername')
      ->where('buyer_id', '=', LoggedIn::user()->id)->get();

    $sales = Auction::join('users AS users_seller', 'auctions.seller_id', '=', 'users_seller.id')
      ->leftJoin('users AS users_buyer', 'auctions.buyer_id', '=', 'users_buyer.id')
      ->select('auctions.*', 'users_seller.username AS sellername', 'users_buyer.username AS buyername')
      ->where('auctions.seller_id', '=', LoggedIn::user()->id)->get();

    echo $this->twig->render('myAuctioneer.html', [
      'session' => LoggedIn::user(),
      'bids' => $bids,
      'sales' => $sales]);
  }

  public function getShowListAuction()
  {
    echo $this->twig->render('listAuction.html', [
      'session' => LoggedIn::user()]);
  }

  public function postShowListAuction()
  {
    $validation_data = [
      "title"         => "min:5",
      "description"   => "min:15",
      "starttime"     => "min:3",
      "duration"      => "min:1",
      "starting_price" => "min:1",
      "imageFile"      => "min:1",
    ];
    # validate data
    $validator = new Validator;

    $errors = $validator->isValid($validation_data);

    if(count($errors))
    {
      echo $this->twig->render('listAuction.html', [
        'session' => LoggedIn::user(),
        'notification' => $errors,
        'type' => 'error']);
      exit();
    }

    # save auction in db
    /*$auction = new Auction();
    $auction->title = $_REQUEST['title'];
    $auction->description = $_REQUEST['description'];
    $auction->starttime = $_REQUEST['starttime'];
    $auction->endtime = date("Y-m-d H:i:s",
        strtotime($_REQUEST['starttime']) + 60*60*24* $_REQUEST['duration']);
    $auction->starting_price = $_REQUEST['starting_price'];
    $auction->save();*/

    $endtime = date("Y-m-d H:i:s",
        strtotime($_REQUEST['starttime']) + 60*60*24* $_REQUEST['duration']);
    $auctionId = Auction::insertGetId([
        'title' => $_REQUEST['title'],
        'description' => $_REQUEST['description'],
        'starttime' => $_REQUEST['starttime'],
        'endtime' => $endtime,
        'starting_price' => $_REQUEST['starting_price'],
      ]);

    # save image file and path
    $image = new Image();
    $image->auction_id = $auctionId;
    $image->path = $_REQUEST['path'];
    $image->save();

    echo $this->twig->render('listAuction.html', [
      'session' => LoggedIn::user(),
      'notification' => ['Successful listed!'],
      'type' => 'info']);
  }

  public function getShowBidAuction($array)
  {
    if(isset($array['id']))
    {
      $id = $array['id'];
    } else {
      $id = 0;
    }

    $auctions = Auction::find($id);

    if(isset($auctions))
    {
      $auctions = Auction::where('id', '=', $id)->get();
      $auctions[0]->current_price = Beautify::amount($auctions[0]->current_price);
      $auctions[0]->endtime = Beautify::date($auctions[0]->endtime);

      echo $this->twig->render('bidAuction.html', [
        'session' => LoggedIn::user(),
        'auction' => $auctions[0]]);
    } else {
      echo $this->twig->render('notFound.html', [
        'session' => LoggedIn::user(),
        ]);
    }
  }

  public function postShowBidAuction()
  {
    echo $this->twig->render('bidAuction.html', [
      'session' => LoggedIn::user()]);
  }

  public function getShowActiveAuctions()
  {
    $auctions = [];

    $auctions = Auction::leftJoin('users AS users_buyer', 'auctions.buyer_id', '=', 'users_buyer.id')
      ->leftJoin('users AS users_seller', 'auctions.seller_id', '=', 'users_seller.id')
      ->select('auctions.*', 'users_seller.username AS sellername', 'users_buyer.username AS buyername')
      ->where('status', '=', 'running')->get();

    echo $this->twig->render('activeAuctions.html', [
      'session' => LoggedIn::user(),
      'auctions' => $auctions]);
  }
}
