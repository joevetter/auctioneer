<?php
namespace Auctioneer\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
  public function auctions()
  {
    return $this->hasMany('Auctioneer\models\Auction');
  }

  public function bids()
  {
    return $this->hasMany('Auctioneer\models\Bid');
  }
}
