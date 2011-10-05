<?php
	define('ROOT', '../..');
	include ROOT . '/lib/includeForAjax.php';

	requireStrictRoute();

	$response = array();
	$response['error'] = 0;
	$response['message'] = '';
	
	$id = $_POST['id'];
	$name = $_POST['name'];

	if(empty($id) || empty($name)) {
			$response['error'] = -1;
			$response['message'] = _t('잘못된 접근입니다.');
	} else {
		if (!isAdmin()) {
			$response['error'] = 1;
			$response['message'] = _t('관리자만이 이 기능을 사용할 수 있습니다.');
		} else {
			requireComponent('Bloglounge.Data.Groups');
			if(!Group::edit($id, $name)) {
				$response['error'] = 2;
				$response['message'] = _t('그룹수정을 실패하였습니다.');
			} else {
				$response['message'] = $name;
			}
		}
	}

	func::printRespond($response);
?>