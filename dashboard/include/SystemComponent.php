<?php 
class SystemComponent {
	var $settings;
	
	function getSettings() {
		// IP Lokal
		// $settings['dbhost'] = '10.10.10.95,8198\MANUNGGALDB';
		
		// IPTX
		$settings['dbhost'] = 'localhost,1433';

		$settings['dbusername'] = 'sa';
		$settings['dbpassword'] = 'P@ssw0rd!';
		$settings['dbname'] = 'maxi-line';
		
		return $settings;
	}
}
?>