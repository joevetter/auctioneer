<?php
namespace Auctioneer\Controllers;

use Auctioneer\Models\User;
use Auctioneer\General\LoggedIn;
use Auctioneer\General\Validator;

class RegisterController extends BaseController
{

  public function getShowRegisterPage()
  {
    echo $this->twig->render('register.html',[
      'session' => LoggedIn::user()]);
  }

  public function postShowRegisterPage()
  {
    $validation_data = [
      "firstname"    => "min:3",
      "lastname"     => "min:3",
      "username"      => "min:3",
      "email"         => "email|equalTo:verify_email",
      "verify_email"  => "email",
      "password"      => "min:5|equalTo:verify_password",
    ];
    # validate data
    $validator = new Validator;

    $errors = $validator->isValid($validation_data);

    if(count($errors))
    {
      echo $this->twig->render('register.html', [
        'session' => LoggedIn::user(),
        'notification' => $errors,
        'type' => 'error']);
      exit();
    }

    # save user in db
    $user = new User();
    $user->firstname = $_REQUEST['firstname'];
    $user->lastname = $_REQUEST['lastname'];
    $user->username = $_REQUEST['username'];
    $user->email = $_REQUEST['email'];
    $user->password = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
    $user->save();

    $_SESSION['user'] = $user;

    # render home page
    echo $this->twig->render('home.html', [
      'session' => LoggedIn::user(),
      'notification' => ['Successful registered!'],
      'type' => 'info']);
  }

}
