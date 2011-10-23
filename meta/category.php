<?php
	define('ROOT', '..');
	include ROOT . '/lib/include.php';

	$searchType = 'category';
	$searchKeyword = func::decode($accessInfo['action']);

	include ROOT . '/lib/begin.php';
	
	if(empty($searchKeyword)) {
		// TODO: 정렬옵션, 포스트 개수 스킨 옵션에 추가
		
		$categories = Category::getCategoriesAll('id, name, count');
		
		foreach($categories as $categoryKey=>$category) {
			$categories[$categoryKey]['posts'] = $posts;
			
			unset($posts);
		}
		
		include ROOT . '/lib/piece/categorylist.php';

		
	} else {
		$customQuery = $event->on('Query.feedItems', '');
	
		// 글 목록
		$pageCount = $skinConfig->postList; // 페이지갯수
		list($posts, $totalFeedItems) = FeedItem::getFeedItems($searchType, $searchKeyword, $searchExtraValue, $page, $pageCount, false, 0, $customQuery);
		$paging = Func::makePaging($page, $pageCount, $totalFeedItems);
	
		include ROOT . '/lib/piece/message.php';
		include ROOT . '/lib/piece/postlist.php';
	}
		
	include ROOT . '/lib/end.php';
?>