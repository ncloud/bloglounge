<?php
	if(isset($categories)) {
		$s_categories = '';
		$src_categories = $skin->cutSkinTag('categorylist');	
		$subpath = empty($accessInfo['subpath'])?'':'/'.func::firstSlashDelete($accessInfo['subpath']);
		
		$skinConfig->categoryListNameLength = 30;

		if(count($categories) > 0) {
			$s_categories_rep = '';
			$src_category_rep = $skin->cutSkinTag('categorylist_rep');		
			$index = 0;
			foreach ($categories as $category) {	
				$index ++;
				$category = $event->on('Data.category', $category);
				
				$sp_categories = $skin->parseTag('category_name', $event->on('Text.categoryName', UTF8::lessenAsByte($category['name'], $skinConfig->categoryListNameLength)), $src_category_rep);

				$s_categoryrecent = '';
				$src_categoryrecent = $skin->cutSkinTag('categoryrecent');
				$src_categoryrecent_rep = $skin->cutSkinTag('categoryrecent_rep');
				$s_categoryrecent_rep = '';

					if ($category['posts']) {	
						$sp_categoryrecent_rep = '';
						foreach($category['posts'] as $post) {
							$link_url = $config->addressType == 'id' ? $service['path'].'/go/'.$post['id'] : $service['path'].'/go/'.$post['permalink'];

							$post['thumbnail'] = '';
							if($media = Media::getMedia($post['thumbnailId'])) {
								$post['thumbnail'] = $media['thumbnail'];	
							}
							$src_thumbnail = $skin->cutSkinTag('cond_thumbnail', $src_category_rep);
							$thumbnailFile =  $event->on('Text.postThumbnail', Media::getMediaFile($post['thumbnail']));
		
							if(!empty($thumbnailFile)) {
								$s_thumbnail = (!Validator::is_empty($thumbnailFile)) ? $skin->parseTag('categories_recent_thumbnail', $thumbnailFile, $src_thumbnail) : '';
								$s_categoryrecent_rep = $skin->dressOn('cond_thumbnail', $src_thumbnail, $s_thumbnail, $src_categoryrecent_rep);	
								$s_categoryrecent_rep = $skin->parseTag('categories_recent_thumbnail_exist', 'post_thumbnail_exist', $s_categoryrecent_rep);
							} else {
								$s_categoryrecent_rep = $skin->dressOn('cond_thumbnail', $src_thumbnail, '', $src_categoryrecent_rep);
								$s_categoryrecent_rep = $skin->parseTag('categories_recent_thumbnail_exist', 'post_thumbnail_nonexistence', $s_categoryrecent_rep);
							}
							
							$s_categoryrecent_rep = $skin->parseTag('categories_recent_url', $post['permalink'], $s_categoryrecent_rep);
							$s_categoryrecent_rep = $skin->parseTag('categories_recent_linkurl', $link_url, $s_categoryrecent_rep);
							$s_categoryrecent_rep = $skin->parseTag('categories_recent_title', $post['title'], $s_categoryrecent_rep);
							$s_categoryrecent_rep = $skin->parseTag('categories_recent_date', date('Y-m-d H:i',$post['written']), $s_categoryrecent_rep);
							
							
							$sp_categoryrecent_rep .= $s_categoryrecent_rep;
						}								
						
						$s_categoryrecent .= $skin->dressOn('categoryrecent_rep', $src_categoryrecent_rep, $sp_categoryrecent_rep, $src_categoryrecent);

					} else {
						$s_categoryrecent = '';
					}

				$sp_categories = $skin->parseTag('category_position', ($index==1?'firstItem':($index==count($categories)?'lastItem':'')), $sp_categories);

				$sp_categories = $skin->dressOn('categoryrecent', $src_categoryrecent, $s_categoryrecent, $sp_categories);

			//	$sp_categories = $skin->parseTag('categories_desc', $event->on('Text.feedDescription', UTF8::lessenAsByte($feed['description'], 200)), $sp_categories);
			//	$sp_categories = $skin->parseTag('categories_created', $event->on('Text.feedCreated', (Validator::is_digit($feed['created']) ? date('Y-m-d H:i', $feed['created']) : $feed['created'])), $sp_categories);
			//	$sp_categories = $skin->parseTag('categories_lastupdate', $event->on('Text.feedLastupdate', (Validator::is_digit($feed['lastUpdate']) ? date('Y-m-d H:i', $feed['lastUpdate']) : $feed['lastUpdate'])), $sp_categories);
				$sp_categories = $skin->parseTag('categories_count', $category['count'], $sp_categories);
				$sp_categories = $skin->parseTag('categories_linkurl', $service['path'].'/category/'.$category['name'], $sp_categories);
				$sp_categories = $skin->parseTag('categories_feedurl', $service['path'].'/rss/category/'.$category['id'], $sp_categories);

				$s_categories_rep .= $event->on('Text.category', $sp_categories);
				$sp_categories = '';
			}		

			$s_categories = $skin->dressOn('categorylist_rep', $src_category_rep, $s_categories_rep, $src_categories);

		} else {
			$s_categories_rep = '<div class="no_category">'._t("분류 목록이 비어있습니다.").'</div>';	
			$s_categories = $skin->dressOn('categorylist_rep', $src_category_rep, $s_categories_rep, $src_categories);
		}

		$skin->dress('categorylist', $s_categories);
	}
?>