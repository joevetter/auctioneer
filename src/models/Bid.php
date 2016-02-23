<?php
namespace Auctioneer\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Bid extends Eloquent
{
  public function user()
  {
    return $this->hasOne('Auctioneer\models\User');
  }

  public function auction()
  {
    return $this->hasOne('Auctioneer\models\Auction');
  }
}
