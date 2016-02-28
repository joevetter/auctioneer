<?php
namespace Auctioneer\Controllers;

use Auctioneer\General\LoggedIn;
use Auctioneer\General\Validator;
use Auctioneer\General\Beautify;
use Auctioneer\Models\Auction;
use Auctioneer\Models\Image;
use Auctioneer\Models\Bid;

class AuctionController extends BaseController
{

  public function getShowMyAuctions()
  {
    $this->updateAllStati();

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
    $this->updateAllStati();

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
      "images"        => "upload",
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
    $starttime = date("Y-m-d", strtotime($_REQUEST['starttime'])
        + strtotime(date("H:i:s")));
    $endtime = date("Y-m-d H:i:s",
        strtotime($starttime) + 60*60*24* $_REQUEST['duration']);
    $status = $this->getStatus($starttime, $endtime);

    $auctionId = Auction::insertGetId([
        'title' => $_REQUEST['title'],
        'description' => $_REQUEST['description'],
        'starttime' => $starttime,
        'endtime' => $endtime,
        'starting_price' => $_REQUEST['starting_price'],
        'current_price' => $_REQUEST['starting_price'],
        'seller_id' => LoggedIn::user()->id,
        'status' => $status,
      ]);
    $notification = ['Successful listed!'];

    # save image file and path
    if(isset($_FILES["images"]) && is_numeric($auctionId))
    {
      $folders = array_merge(["assets", "images_auction"],
          explode("-", date("Y-m-d")),
          [$auctionId]);
      $path = __DIR__ . "/../../public";
      foreach($folders as $folder)
      {
        $path .= "/" . $folder;
        if(!is_dir($path))
        {
          mkdir($path);
        }
      }

      foreach ($_FILES["images"]["error"] as $key => $error) {
          if ($error == UPLOAD_ERR_OK) {
              $tmp_name = $_FILES["images"]["tmp_name"][$key];
              $name = $_FILES["images"]["name"][$key];
              move_uploaded_file($tmp_name, $path . "/" . $name);

              $image = new Image();
              $image->auction_id = $auctionId;
              $image->path = $path . "/" . $name;
              $image->save();
          } else {
            $notification = ['Image not uploaded!'];
          }
      }
    } else {
      $notification = ['There was an error!'];
    }

    echo $this->twig->render('listAuction.html', [
      'session' => LoggedIn::user(),
      'notification' => $notification,
      'type' => 'info']);
  }

  public function getShowBidAuction($array)
  {
    $this->updateAllStati();

    if(isset($array['id']))
    {
      $id = $array['id'];
    } else {
      $id = 0;
    }

    $auctions = Auction::find($id);

    if(isset($auctions))
    {
      $auctions = Auction::join('users AS users_seller', 'auctions.seller_id', '=', 'users_seller.id')
        ->leftJoin('users AS users_buyer', 'auctions.buyer_id', '=', 'users_buyer.id')
        ->leftJoin('images', 'auctions.id', '=', 'images.auction_id')
        ->select('auctions.*',
                 'users_seller.username AS sellername',
                 'users_buyer.username AS buyername',
                 'images.path AS imagepath')
        ->where('auctions.id', '=', $id)->first();

      if(isset($auctions->current_price)){
        $auctions->minimumBid = Beautify::amount($auctions->current_price + 0.5);
        $auctions->current_price = Beautify::amount($auctions->current_price);
        $auctions->endtime = Beautify::date($auctions->endtime);
        $auctions->imagepath = Beautify::imagePath($auctions->imagepath);

        $bidCount = [];
        $bids = Bid::where('auction_id', '=', $id)->get();
        foreach($bids as $bid)
        {
          $bidCount[$bid->bidder_id] = $bid->bidder_id;
        }
        $bidCount = count($bidCount);

        $auctions->bidCount = $bidCount;

        echo $this->twig->render('bidAuction.html', [
          'session' => LoggedIn::user(),
          'auction' => $auctions]);
      } else {
        echo $this->twig->render('notFound.html', [
          'session' => LoggedIn::user(),
          ]);
      }
    } else {
      echo $this->twig->render('notFound.html', [
        'session' => LoggedIn::user(),
        ]);
    }
  }

  public function postShowBidAuction()
  {
    $auctionId = $_REQUEST["auctionId"];
    $newOffer = $_REQUEST["newOffer"];

    if(is_numeric($auctionId) && is_numeric($newOffer))
    {
      $result = $this->placeNewOffer($auctionId, $newOffer);

      if($result)
      {
        echo json_encode( ["status" => true,
                          "notification" => "Successfully bid!",
                        ]);
      } else {
        echo json_encode( ["status" => false,
                          "notification" => "Auction ended or your offer was invalid!",
                        ]);
      }
    } else {
      echo json_encode( ["status" => false,
                        "notification" => $newOffer . " is not a valid amount! ",
                      ]);
    }
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

  private function getStatus($starttime, $endtime)
  {
    if($starttime > date("Y-m-d H:i:s"))
    {
      return "tba";
    } elseif($endtime < date("Y-m-d H:i:s")) {
      return "ended";
    } else {
      return "running";
    }
  }

  private function setStatus($auction, $status)
  {
    if(is_numeric($auction) && isset($status))
    {
      Auction::where('id', '=', $auction)
        ->update(['status' => $status]);
      return true;
    } else {
      return false;
    }
  }

  private function updateAllStati()
  {
    $now = date("Y-m-d H:i:s");

    Auction::where([
        ['status', '!=', 'ended'],
        ['endtime', '<', $now],
      ])
      ->update(['status' => 'ended']);

    Auction::where([
        ['status', '!=', 'ended'],
        ['status', '!=', 'running'],
        ['starttime', '<', $now],
        ['endtime', '>', $now],
      ])
      ->update(['status' => 'running']);
  }

  private function placeNewOffer($auctionId, $newOffer)
  {
    $updateCount = Auction::where([
        ['id', '=', $auctionId],
        ['current_price', '<', $newOffer],
      ])
      ->update(['current_price' => $newOffer,
                'buyer_id' => LoggedIn::user()->id,
              ]);

    return $updateCount;
  }
}
