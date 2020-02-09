<?php
	require_once '../denko/dk.denko.php';
	Denko::noCache();
	if(!empty($_GET['lang']) && strlen($_GET['lang'])==2){
		setCookie('lang',$_GET['lang']);
	}
	if(isset($_SERVER['HTTP_REFERER'])) Denko::redirect($_SERVER['HTTP_REFERER']);
	Denko::redirect('./');
