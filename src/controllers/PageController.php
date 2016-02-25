<?php
namespace Auctioneer\Controllers;

use Auctioneer\Models\User;
use Auctioneer\Models\Page;
use Auctioneer\General\LoggedIn;
use Auctioneer\Models\Auction;

class PageController extends BaseController
{

  public function getShowHomePage()
  {
    echo $this->twig->render('home.html',[
      'session' => LoggedIn::user()]);
  }

  public function getShowPage()
  {
    $title = '';
    $content = '';

    # extract page name from url
    $uri = explode("/", $_SERVER['REQUEST_URI']);
    $target = $uri[1];
    # find matching page in the db
    $page = Page::where('slug', '=', $target)->get();
    # look up page content
    foreach($page as $row)
    {
      $title = $row->title;
      $content = $row->content;
    }

    if(empty($title))
    {
      header("HTTP/1.0 404 Not Found");
      header("Location: /notFound");
      exit();
    }
    # pass content to twig te
    echo $this->twig->render('genericPage.html',[
      'session' => LoggedIn::user(),
      'title'   => $title,
      'content' => $content]);
  }

  public function getShowNotFound()
  {
    echo $this->twig->render('notFound.html', [
      'session' => LoggedIn::user(),
      ]);
  }

  public function getShowTest()
  {
    echo '<form action="/test" method="post" enctype="multipart/form-data">
        <input type="file" name="pictures[]">
        <input type="hidden" name="my_secret" value="secret field">
        <button type="submit" class="btn btn-primary">upload</button>
      </form>';

    var_dump(extension_loaded("fileinfo"));

    exit();

    $username = LoggedIn::user()->username;
    var_dump($username);

    $bids = Auction::join('users AS users_seller', 'auctions.seller_id', '=', 'users_seller.id')
      ->leftJoin('users AS users_buyer', 'auctions.buyer_id', '=', 'users_buyer.id')
      ->select('auctions.*', 'users_seller.username AS sellername', 'users_buyer.username AS buyername')
      ->where('auctions.seller_id', '=', LoggedIn::user()->id)->get();
    var_dump($bids);
    print_r($bids[0]);
  }

  public function postShowTest()
  {
    if(isset($_FILES["pictures"]))
    {
      var_dump($_FILES);
      echo "<br>-----<br>";
      var_dump($_REQUEST);
      echo "<br>-----<br>";

      $folders = array_merge(["assets", "images_auction"],
          explode("-", date("Y-m-d")),
          ["4"]);
      $path = __DIR__ . "/../../public";
      foreach($folders as $folder)
      {
        $path .= "/" . $folder;
        if(!is_dir($path))
        {
          mkdir($path);
          #echo "<br>- path:" . $path;
        } else {
          #echo "<br>+ path:" . $path;
        }
      }
      #echo "path:" . $path . " file:" . __FILE__ . " dir:" . __DIR__; exit();

      foreach ($_FILES["pictures"]["error"] as $key => $error) {
          if ($error == UPLOAD_ERR_OK) {
              $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
              $name = $_FILES["pictures"]["name"][$key];
              move_uploaded_file($tmp_name, $path . "/" . $name);
          }
      }
      var_dump($_FILES);
    } else {
      echo "<br>no no no";
      var_dump($_FILES);
    }
  }

}
