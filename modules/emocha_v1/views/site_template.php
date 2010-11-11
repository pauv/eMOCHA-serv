<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>eMOCHA - <?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="/css/main.css">
	<link rel="shortcut icon" href="/favicon.ico">
	<script src="/js/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="/js/main.js" type="text/javascript"></script>
</head>
<body>

	<table id="header">
		<tr>
			<td id="banner">
				<img src="/images/banner_cut.gif">
				<img src="/images/eMOCHA_logo.png" />
			</td>
			<td id="login">
				<h3><?php echo Kohana::config('emocha.version_name'); ?></h3>
				<?php if(isset($logged_in)) { ?>				
					<?php if($logged_in) { ?>
						<p>Logged in as: <?php echo $user->username ?></p>
						<p><?php echo Html::anchor('auth/logout', 'logout') ?></p>
					<?php } else { ?>
						<?php echo Html::anchor('auth/login', 'login') ?>
						<?php echo Html::anchor('auth/register', 'register') ?>
					<?php } ?>
				<?php } ?>	
			</td>
		</tr>
	</table>
	
	<?php if($logged_in) { ?>
		<ul id="main_menu">
			<li id="mm_main"><?php echo Html::anchor('main', 'Main'); ?></li>
			<li id="mm_edu"><?php echo Html::anchor('edu', 'Training'); ?></li>
			<li id="mm_telemed"><?php echo Html::anchor('telemed', 'TeleMed'); ?></li>
			<li id="mm_stats"><?php echo Html::anchor('stats', 'Data'); ?></li>
			<li id="mm_handsets"><?php echo Html::anchor('handsets', 'Handsets'); ?></li>
			<li id="mm_account"><?php echo Html::anchor('account', 'Your Account'); ?></li>
			<?php if($is_admin_user) { ?>
				<li id="mm_admin"><?php echo Html::anchor('admin', 'Admin'); ?></li>
			<?php } ?>
		</ul>
		
		<table id="main_table">
			<tr>
				<td id="nav" class="noborder">
					<?php if(isset($nav)) { ?>
					<ul>
						<?php echo $nav; ?>		
					</ul>
					<?php } ?>
				</td>
				<td class="noborder">
	<?php }
	else { ?>
			<p></p>
			<table id="main_table">
				<tr>
					<td class="noborder">
	<?php } ?>
	
	
	
	
	
	<?php 
	// template content
	echo $content; ?>
	
	
	
	
	
	
				</td>
		</tr>
	</table>

	<div id="status"></div>
		
	<script type="text/javascript">
		$(function() {
			<?php 
				if (isset($curr_menu)) {
					print "$('#mm_$curr_menu').addClass('current');\n";
				}
				if (isset($curr_nav)) {
					print "$('#nav_$curr_nav').addClass('current');\n";
				}
			?>
			// make the whole LI clickable, and not only the A
			var li_click = function(event) {
				var $target = $(event.target);
				if( $target.is("li") ) {
					document.location = $target.children('a').eq(0).attr('href');
				}				
			}
			$('#main_menu LI').click(li_click);
			$('#nav LI').click(li_click);
		});
	</script>
	

</body>
</html>
