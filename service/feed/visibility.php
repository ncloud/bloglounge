<?php
	define('ROOT', '../..');
	include ROOT . '/lib/includeForAjax.php';

	requireStrictRoute();

	$response = array();
	$response['error'] = 0;
	$response['message'] = '';
	
	$id = $_POST['id'];
	$value = $_POST['value'];

	if(empty($id) || empty($value) || !in_array($value, array('y','n'))) {
			$response['error'] = -1;
			$response['message'] = _t('잘못된 접근입니다.');
	} else {
		if (!isLoggedIn()) {
			$response['error'] = 1;
			$response['message'] = _t('로그인 한 사람만 이 기능을 사용할 수 있습니다.');
		} else {
			$ids = explode(',', $id);

			foreach($ids as $id) {		
				if(empty($id)) continue;

				$feed = Feed::getAll($id);
				if(isAdmin() || $feed['owner'] == getLoggedId()) {
					Feed::edit($id,array('visibility'=>$value));
				} else {
					$response['error'] = -1;
					$response['message'] = _t('잘못된 접근입니다.');
					break;
				}
			}
		}
	}

	func::printRespond($response);
?>
