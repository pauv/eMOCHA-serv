<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>eMOCHA - <?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Kohana::config('assets.css_folder'); ?>/reset.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Kohana::config('assets.css_folder'); ?>/main.css">
	<link rel="shortcut icon" href="favicon.ico">
	<script src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jquery-1.4.3.min.js" type="text/javascript"></script>
	<script src="<?php echo Kohana::config('assets.javascript_folder'); ?>/main.js" type="text/javascript"></script>
</head>
<body>

<!-- HEADER -->
<div id="header">
	<table>
		<tr>
			<td><img src="<?php echo Kohana::config('assets.images_folder'); ?>/banner_cut.gif">
				<img src="<?php echo Kohana::config('assets.images_folder'); ?>/eMOCHA_logo.png" />
				</td>
			
			<td id="login" align="right">
				<h3><?php echo $version_name; ?></h3>
				<?php if(isset($logged_in)) { ?>				
					<?php if($logged_in) { ?>
						Logged in as: <?php echo $user->username ?>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo Html::anchor('auth/logout', 'logout') ?>
					<?php } else { ?>
						<?php echo Html::anchor('auth/login', 'login') ?>
						<?php echo Html::anchor('auth/register', 'register') ?>
					<?php } ?>
				<?php } ?>	
			</td>
		</tr>
	</table>
	<div id="menu">
	<?php if($logged_in) {
		echo View::factory('menu')
		->bind('is_admin_user', $is_admin_user)
		->bind('enable_alerts', $enable_alerts);
		} ?>
	</div>
</div>

<!-- HEADER ENDS -->

<!-- SUBMENU -->
<div id="submenu"><?php if($logged_in) { ?>
					<?php if(isset($nav)) { ?>
					<ul>
						<?php echo $nav; ?>		
					</ul>
					<?php } ?>
					<?php } else { ?>
					<br />
					<?php } ?>
				</div>

<div id="subheader"><h1><?php echo $title; ?></h1></div>

	
	
<div id="content">	
	
	<?php 
	// template content
	echo $content; ?>
	

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
</div>
</body>
</html>
