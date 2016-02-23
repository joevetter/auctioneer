<?php
namespace Auctioneer\Controllers;

use Auctioneer\Models\User;
use Auctioneer\General\LoggedIn;

class AuthenticationController extends BaseController
{

  public function getShowLoginPage()
  {
    echo $this->twig->render('login.html', [
      'session' => LoggedIn::user()]);
  }

  public function postShowLoginPage()
  {
    $isUserVerified = false;
    # look up user
    $user = User::where('email', '=', $_REQUEST['email'])
                ->first();

    # validate credentials
    if(!empty($user))
    {
      if(password_verify($_REQUEST['password'], $user->password))
      {
        # if valid -> log in
        $_SESSION['user'] = $user;

        echo $this->twig->render('home.html', [
          'session' => LoggedIn::user(),
          'notification' => ['Successful login!'],
          'type' => 'info']);
        exit();
      }
    }

    # if not valid -> redirect to login page
    unset($_SESSION['user']);

    echo $this->twig->render('login.html', [
      'session' => LoggedIn::user(),
      'notification' => ['Invalid login!'],
      'type' => 'error']);
    exit();
  }

  public function getLogout()
  {
    unset($_SESSION['user']);
    session_destroy();
    echo $this->twig->render('login.html', [
      'session' => LoggedIn::user(),
      'notification' => ['Successful logout!'],
      'type' => 'info']);
    exit();
  }

}
