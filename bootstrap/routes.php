<?php

$router->map('GET', '/', 'Auctioneer\Controllers\PageController@getShowHomePage', 'home');
$router->map('GET', '/notFound', 'Auctioneer\Controllers\PageController@getShowNotFound', 'notfound');
# register routes
$router->map('GET', '/register', 'Auctioneer\Controllers\RegisterController@getShowRegisterPage', 'register');
$router->map('POST', '/register', 'Auctioneer\Controllers\RegisterController@postShowRegisterPage', 'register_post');
# authentication routes
$router->map('GET', '/login', 'Auctioneer\Controllers\AuthenticationController@getShowLoginPage', 'login');
$router->map('POST', '/login', 'Auctioneer\Controllers\AuthenticationController@postShowLoginPage', 'login_post');
$router->map('GET', '/logout', 'Auctioneer\Controllers\AuthenticationController@getLogout', 'logout');
# auction routes
$router->map('GET', '/myAuctioneer', 'Auctioneer\Controllers\AuctionController@getShowMyAuctions', 'myauctioneer');
$router->map('GET', '/listAuction', 'Auctioneer\Controllers\AuctionController@getShowListAuction', 'listauction');
$router->map('POST', '/listAuction', 'Auctioneer\Controllers\AuctionController@postShowListAuction', 'listauction_post');
$router->map('GET', '/bidAuction/[i:id]', 'Auctioneer\Controllers\AuctionController@getShowBidAuction', 'bidauction');
$router->map('POST', '/bidAuction/[i:id]', 'Auctioneer\Controllers\AuctionController@postShowBidAuction', 'bidauction_post');
$router->map('GET', '/activeAuctions', 'Auctioneer\Controllers\AuctionController@getShowActiveAuctions', 'activeauctions');
# test
$router->map('GET', '/test', 'Auctioneer\Controllers\PageController@getShowTest', 'test');
# generic routes
$router->map('GET', '/[*]', 'Auctioneer\Controllers\PageController@getShowPage', 'generic_page');
