<?php
namespace Auctioneer\General;

use Respect\Validation\Validator as Valid;

class Validator
{
  public function isValid($validation_data)
  {
    $errors = [];

    foreach($validation_data as $element => $value)
    {
      $rules = explode('|', $value);

      foreach($rules as $rule)
      {
        $exploded = explode(':', $rule);

        switch($exploded[0])
        {
          case 'min':
            if(false == Valid::stringType()->length($exploded[1])->Validate($_REQUEST[$element]))
            {
              $errors[] = $element . ' must be at least ' . $exploded[1] . ' characters long!';
            }
            break;
          case 'email':
            if(false == Valid::email()->Validate($_REQUEST[$element]))
            {
              $errors[] = $element . ' must be a valid e-mail address!';
            }
            break;
          case 'equalTo':
            if(false == Valid::equals($_REQUEST[$element])->Validate($_REQUEST[$exploded[1]]))
            {
              $errors[] = $element . ' does not match verification value!';
            }
            break;
          default:
            $errors[] = $element . ' not found!';
        }
      }
    }

    return $errors;
  }
}
