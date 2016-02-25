<?php
namespace Auctioneer\General;

class Beautify
{
  protected static $currency = '€';
  protected static $locale = 'DE';

  public static function amount($amount)
  {
    return str_replace('.', ',', round($amount, 2)) . ' ' . self::$currency;
  }

  public static function date($datetime)
  {
    switch(self::$locale)
    {
      case 'DE':
        return date("d.m.Y H:i:s", strtotime($datetime));
      case 'US':
        return date("m/d/Y H:i:s", strtotime($datetime));
      default:
        return date("d.m.Y H:i:s", strtotime($datetime));
    }
  }

  public static function imagePath($path)
  {
    return substr($path, strpos($path, "/assets"));
  }
}
