	<script language="javascript">
		
		function set_language() {
			$.ajax({
				   url: "<?php echo Url::site('language/set'); ?>/"+$('#language_selector').val(),
				   success: function(data){
					//$("#rate_back").html(data);
					//getStats();
					window.location.reload()
				  }
			});
			return false;
		}
		
	</script>
		<div style="float:right; padding-right:20px">Phone language group: <?php
				$language = Session::instance()->get('language');
				echo Form::select('language', 
								Kohana::config('language.languages'), 
								$language, 
								array('id'=>'language_selector','onchange'=>'set_language()'));
			?>
			</div>
			<br class="clear_float" />
			<ul id="main_menu">
			<li id="mm_edu"><?php echo Html::anchor('edu', 'Training'); ?></li>
			<li id="mm_messages"><?php echo Html::anchor('messages', 'Messages'); ?></li>
			<li id="mm_handsets"><?php echo Html::anchor('handsets', 'Handsets'); ?></li>
			<li id="mm_account"><?php echo Html::anchor('account', 'Your Account'); ?></li>
			<?php if($is_admin_user) { ?>
				<li id="mm_admin"><?php echo Html::anchor('admin', 'Admin'); ?></li>
			<?php } ?>
		</ul>
		