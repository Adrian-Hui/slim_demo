<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

//include all php file in services directory
spl_autoload_register(function($class){
  include(__DIR__ . '/../services/'. $class. '.php');
});

//database credential saves in seperate file
$config = include(__DIR__ . '/../config/cred.php');

//Should set to false on production
$config['displayErrorDetails'] = false;
$config['addContentLengthHeader'] = false;


$container = new \Slim\Container(["settings"=>$config]);

$app = new \Slim\App($container);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("odbc:".$db['host'],$db['user'],$db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;

};


$container['coupon'] = function ($c) {
  return new coupon_function($c->get('db'));
};

$container['common'] = function($c) {
  return new common_function($c->get('db'));
};

$container['member'] = function($c){
  return new member_function($c->get('db'));
};

$app -> get('/user_coupon',\coupon::class .':coupon_list');

$app -> post('/coupon_auth',\coupon::class .':coupon_auth');

$app -> post('/member_register',\member::class .':member_register');

$app -> get('/member_exist',\member::class .':member_exist');

$app -> post('/phone_verify', \member::class .':phone_verify');

$app -> get('/whcode', \common::class.':whcode');

$app ->run();
