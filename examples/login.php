<?php

$type = $_GET['type'];
include '../src/OKauth/Application.php';



//重定向到授权引导页面
$app = new \OKauth\Applocation($type);
app->authorize_url();