
		<ul id="main_menu">
			<li id="mm_stats"><?php echo Html::anchor('stats', 'Data'); ?></li>
			<li id="mm_handsets"><?php echo Html::anchor('handsets', 'Handsets'); ?></li>
			<li id="mm_account"><?php echo Html::anchor('account', 'Your Account'); ?></li>
			<?php if($is_admin_user) { ?>
				<li id="mm_admin"><?php echo Html::anchor('admin', 'Admin'); ?></li>
			<?php } ?>
		</ul>
		