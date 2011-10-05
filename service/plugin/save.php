<?php
	// save configuration
	define('ROOT', '../..');
	include ROOT . '/lib/includeForAjax.php';

	requireStrictRoute();

	$response = array();
	$response['error'] = 1;
	$response['message'] = '';
	
	if (!isAdmin()) {
		$response['error'] = 1;
		$response['message'] = _t('�����ڸ��� �� ����� ����� �� �ֽ��ϴ�.');
	} else {
		$index=0;
		$fields = array();
		$pluginName = $_POST['pluginName'];
		$types = explode('|', $_POST['fieldTypes']);
		foreach ($_POST as $key=>$value) {
			if (Validator::enum($key, 'fieldTypes,pluginName')) continue;
			$type = $types[$index];
			$key = substr($key, 1, strlen($key)-1);
			array_push($fields, array('name'=>$key, 'value'=>$value, 'type'=>$type, 'isCDATA'=>(strtolower($type)=='textarea')?true:false));
			$index++;
		}

		if (Plugin::setConfig($pluginName, $fields))
			$response['error'] = 0;
	}


	func::printRespond($response);
?>