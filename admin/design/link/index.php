<?php
	define('ROOT', '../../..');
	include ROOT . '/lib/includeForAdmin.php';

	requireAdmin();

	include ROOT. '/lib/piece/adminHeader.php';

	$skinlist = array();
	$dir = opendir(ROOT . '/skin/link/');
	while ($file = readdir($dir)) {
		if (!file_exists(ROOT . '/skin/link/'.$file.'/index.xml')) continue;
		array_push($skinlist, $file);
	}

	// 현재 사용중인 스킨
	$n_skinname = Settings::get('linkskin');
	$n_skinpath = ROOT . '/skin/link/'.$n_skinname;
	$xmls = new XMLStruct();

	if (file_exists($n_skinpath.'/index.xml')) {
		$xml = file_get_contents($n_skinpath.'/index.xml');
		$xmls->open($xml);
	}

	$pageCount = 15; // 페이지갯수
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	if(!isset($page) || empty($page)) $page = 1;

	$paging = Func::makePaging($page, $pageCount, count($skinlist));

?>

<link rel="stylesheet" href="<?php echo $service['path'];?>/style/admin_design.css" type="text/css" />
<script type="text/javascript">
	function saveSkin(name) {
		$.ajax({
		  type: "POST",
		  url: _path +'/service/design/skin.php',
		  data: 'name=' + encodeURIComponent(name) + "&type=link",
		  dataType: 'xml',
		  success: function(msg){		
			error = $("response error", msg).text();
			if(error == "0") {
				document.location.reload();
			} else {
				alert($("response message", msg).text());
			}
		  },
		  error: function(msg) {
			 alert('unknown error');
		  }
		});
	}
</script>

<div class="wrap title_wrap">
	<h3><?php echo _t("현재 사용중인 링크스킨");?></h3>
</div>

<div class="wrap select_skin_wrap">
	<?php echo drawAdminBoxBegin('select_skin');?>
		<div class="select_skin_data">
<?php
	if(isset($xml)) {
?>
			<div class="thumbnail">			
				<img src="<?php echo $n_skinpath;?>/preview.gif" alt="<?php echo _t('미리보기 이미지');?>" />
			</div>
			<div class="data">
				<dl class="normal">
					<dt><?php echo _t("스킨명");?></dt>
					<dd class="text"><?php echo $xmls->getValue('/skin/information/name[lang()]');?> ver<?php echo $xmls->getValue('/skin/information/version');?> (<?php echo $n_skinname;?>)</dd>
				</dl>		
				<dl class="normal">
					<dt><?php echo _t("제작자");?></dt>
					<dd class="text"><?php echo $xmls->getValue('/skin/author/name[lang()]');?> <a href="mailto:<?php echo $xmls->getValue('/skin/author/email');?>"><img src="<?php echo $service['path'];?>/images/admin/icon_email.gif" alt="<?php echo _t('이메일 보내기');?>" align="absmiddle" /></a> , <a href="<?php echo $xmls->getValue('/skin/author/homepage[lang()]');?>" target="_blank"><?php echo $xmls->getValue('/skin/author/homepage[lang()]');?></a> </dd>
				</dl>	
				<dl class="normal desc">
					<dt><?php echo _t("설명");?></dt>
					<dd class="text"><?php echo nl2br($xmls->getValue('/skin/information/description[lang()]'));?></dd>
				</dl>			
				<dl class="normal">
					<dt><?php echo _t("라이센스");?></dt>
					<dd class="text"><?php echo nl2br($xmls->getValue('/skin/information/license[lang()]'));?></dd>
				</dl>	
				<div class="tools">
					<a href="#" class="normalbutton" onclick="saveSkin(''); return false;"><span class="boldbutton"><?php echo _t('사용안함');?></span></a>
				</div>
			</div>
			<div class="clear"></div>
<?php
	} else {
?>
		<div class="empty">
			<?php echo _t('현재 사용중인 스킨이 없습니다.');?>
		</div>
<?php
	}
?>
		</div>
	<?php echo drawAdminBoxEnd('');?>
</div>

<div class="wrap list_title_wrap">
	<h3><?php echo _t('링크스킨 목록');?> <span class="cnt">(<?php echo count($skinlist);?>)</span></h3>
	<div class="innerline"></div>
</div>

<div class="wrap list_skin_wrap">
	<ul>
<?php
	$start = ($page-1)*$pageCount;
	$end = ($page)*$pageCount;
	if($end > count($skinlist)) $end = count($skinlist);
	for($i=$start;$i<$end;$i++) {
		$skinname = $skinlist[$i];
		$skinpath = ROOT . '/skin/link/'.$skinname;
		$xml = file_get_contents($skinpath.'/index.xml');
		$xmls->open($xml);
?>
		<li<?php echo $skinname == $n_skinname?' class="selected"':'';?>>
			<div class="thumbnail">		
				<div class="thumbnail_box">
<?php
			if(file_exists($skinpath.'/preview_small.gif')) {
?>
					<img src="<?php echo $skinpath;?>/preview_small.gif" alt="<?php echo _t('미리보기 이미지');?>"/>

<?php
			} else {
?>
					<img src="<?php echo $skinpath;?>/preview.gif" alt="<?php echo _t('미리보기 이미지');?>"/>
<?php
			}

?>
				</div>
			</div>
			<div class="data">
				<div class="title">
					<?php echo $xmls->getValue('/skin/information/name[lang()]');?> <span class="version"><?php echo $xmls->getValue('/skin/information/version');?></span><br />
					<span class="name">(<?php echo $skinname;?>)</span>
				</div>
				<div class="tools">
					<a href="#" class="normalbutton" onclick="saveSkin('<?php echo $skinname;?>'); return false;"><span class="boldbutton"><?php echo _t('적용');?></span></a>
				</div>
			</div>
		</li>
<?php
	}
?>
	</ul>
	<div class="clear"></div>

	<div class="innerline"></div>

	<div class="paging">
		<?php echo func::printPaging($paging);?>
	</div>

</div>

<?php
	include ROOT. '/lib/piece/adminFooter.php';
?>
