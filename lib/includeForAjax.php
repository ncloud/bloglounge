<?php
	include ROOT . '/config.php';	
	include ROOT . '/lib/config.php';

	ini_set('display_errors', false);

	include ROOT . '/lib/init.php';
	if (!defined('NO_SESSION')) include_once(ROOT. '/lib/session.php'); 
	include_once(ROOT. '/lib/auth.php');

	include_once(ROOT. '/lib/components/index.php');	
	
	include_once(ROOT.'/lib/functions.php');	
	
	$caches = new Caches;
?>