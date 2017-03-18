<?php



	/*
	* 授权后的回调页面
	*
	*/



	$type = $_GET['type'];
	include '../src/OKauth/Application.php';


	$app    = new \OKauth\Application($type);
	if(!($app->authorize_callback())){
		var_dump($app->getError());
		exit;
	}

	$oauth_info = $app->get_account_user();

	var_dump($oauth_info);
