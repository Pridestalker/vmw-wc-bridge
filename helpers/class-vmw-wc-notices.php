<?php

class Vmw_Wc_Admin_Notices {
	public static function error($message) {
		static::add_notice($message, 'error');
	}
	
	public static function success($message) {
		static::add_notice($message);
	}
	
	public static function add_notice($message, $type = 'success') {
		add_action('admin_notices', static function() use ($message, $type) {
	        wp_die('NOTIFY');
			?>
			<div class="notice notice-<?=$type?> is-dismissible">
				<p>
					<?=$message?>
				</p>
			</div>
			<?php
		});
	}
}
