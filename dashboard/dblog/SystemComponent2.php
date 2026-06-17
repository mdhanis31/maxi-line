<?php 
class SystemComponent2 {
	var $settings;
	
	function getSettings() {
		// IP Lokal
		// $settings['dbhost'] = '10.10.10.95,8198\MANUNGGALDB';

		// IPTX
		$settings['dbhost'] = '160.20.79.242,8198\MANUNGGALDB';
		$settings['dbusername'] = 'manunggaldb';
		$settings['dbpassword'] = 'M4nun994l01*';
		$settings['dbname'] = 'manunggal_log';
		
		return $settings;
	}
}
?>