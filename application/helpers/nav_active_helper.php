<?php

	function set_active($segment) {
		$CI = get_instance(); // Dapatkan instance CodeIgniter
		return ($CI->uri->segment(1) == $segment) ? 'active' : '';
	}

?>
