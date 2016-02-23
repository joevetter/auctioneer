<?php
namespace Auctioneer\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Image extends Eloquent
{
  public function auction()
  {
    return $this->hasOne('Auctioneer\models\Auction');
  }
}
