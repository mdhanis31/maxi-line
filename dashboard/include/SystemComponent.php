<?php 
class SystemComponent {
	var $settings;
	
	function getSettings() {
		// IP Lokal
		// $settings['dbhost'] = '10.10.10.95,8198\MANUNGGALDB';
		
		// IPTX
		$settings['dbhost'] = 'your-host-database';

		$settings['dbusername'] = 'your-database-username';
		$settings['dbpassword'] = 'your-database-password';
		$settings['dbname'] = 'your-database-name';
		
		return $settings;
	}
}
?>
