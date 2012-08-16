
		<ul id="main_menu">
			<li id="mm_main"><?php echo Html::anchor('main', 'Maps'); ?></li>
			<li id="mm_stats"><?php echo Html::anchor('stats', 'Data'); ?></li>
			<?php if($enable_alerts) { ?>
				<li id="mm_messages"><?php echo Html::anchor('messages', 'Messages'); ?></li>
			<?php } ?>
			<li id="mm_handsets"><?php echo Html::anchor('handsets', 'Handsets'); ?></li>
			<li id="mm_account"><?php echo Html::anchor('account', 'Your Account'); ?></li>
			<?php if($is_admin_user) { ?>
				<li id="mm_admin"><?php echo Html::anchor('admin', 'Admin'); ?></li>
			<?php } ?>
		</ul>
		