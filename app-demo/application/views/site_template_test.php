<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>eMOCHA - <?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="/css/basic.css">
	<link rel="stylesheet" type="text/css" href="/css/tabs.css">
	<link rel="shortcut icon" href="favicon.ico">
	<script src="/js/jquery-1.4.3.min.js" type="text/javascript"></script>
	<script src="/js/main.js" type="text/javascript"></script>
</head>
<body>

	<div id="frame" style:width:950px;">
				<img src="/images/eMOCHA_logo.png" />
			<div id="login" style="float:right">
				<!--<?php echo Kohana::config('emocha.version_name'); ?>-->
				<ul>
				<?php if(isset($logged_in)) { ?>				
					<?php if($logged_in) { ?>
						<li>Logged in as: <?php echo $user->username ?></li>
						<li><?php echo Html::anchor('auth/logout', 'logout') ?></li>
					<?php } else { ?>
						<li><?php echo Html::anchor('auth/login', 'login') ?></li>
						<li><?php echo Html::anchor('auth/register', 'register') ?></li>
					<?php } ?>
				<?php } ?>
				</ul>
			</div>

	<!--
	<div id="imagecontainer" style="position:relative;margin:5 2 0 2;padding:0;height:160px;background: url('http://emocha.org/wp-content/themes/atahualpa/images/header/eMOCHA.org.banner.jpg') top center no-repeat;"></div>
	-->
	<?php if($logged_in) { ?>
	<div id="tabs">
	<ul id="primary">
		<li id="mm_main"><?php echo Html::anchor('main', 'Main'); ?></li>
		<li id="mm_edu"><?php echo Html::anchor('edu', 'Training'); ?></li>
		<!--<li id="mm_sms"><?php echo Html::anchor('sms', 'Sms'); ?></li>-->
		<li id="mm_sms"><span>Sms</span></li>
			<ul id="secondary">
				<li><a href="#">Current Clients</a></li>
				<li><a href="#">Case Studies</a></li>
				<li><a href="#">Publications</a></li>
				<li><a href="#">Reports</a></li>
				<li><a href="#">Whitepapers</a></li>
			</ul>
		</li>
		<li id="mm_stats"><?php echo Html::anchor('stats', 'Data'); ?></li>
		<li id="mm_handsets"><?php echo Html::anchor('handsets', 'Handsets'); ?></li>
		<li id="mm_account"><?php echo Html::anchor('account', 'Your Account'); ?></li>
		<?php if($is_admin_user) { ?>
			<li id="mm_admin"><?php echo Html::anchor('admin', 'Admin'); ?></li>
		<?php } ?>
	</ul>
	</div><?php
	
	}
	?>
	<div id="main">
		<div id="contents">
	
	
	<?php 
	// template content
	echo $content; ?>
	
	
	</div>
	</div>
	
	
	
	
	
	</div>
</body>
</html>
