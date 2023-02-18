<?php
include('vendor/autoload.php');
require_once "App.php";

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new App();
$app->start();


