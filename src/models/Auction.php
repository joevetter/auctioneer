<?php
namespace Auctioneer\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Auction extends Eloquent
{
  public function user()
  {
    return $this->hasOne('Auctioneer\models\User');
  }

  public function bids()
  {
    return $this->hasMany('Auctioneer\models\Bid');
  }

  public function images()
  {
    return $this->hasMany('Auctioneer\models\Image');
  }
}
