<?php
	$config = new Settings();
	$skinConfig = new SkinSettings();

	// ī���ʹ� �Ϸ翡 �ѹ���
	if (!isset($_COOKIE['visited'])) {
		requireComponent('Bloglounge.Data.Stats');
		Stats::visit($config->countRobotVisit);
		setcookie("visited", "bloglounge", time() + 86400, "/", ((substr(strtolower($_SERVER['HTTP_HOST']), 0, 4) == 'www.') ? substr($_SERVER['HTTP_HOST'], 3) : $_SERVER['HTTP_HOST']));
	}	
		
	$event->on('Meta.begin');

	include_once(ROOT.'/lib/skin.begin.php');
?>