<?php 

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
/**
 * Class	: DbConnector
 * Purpose	: Connect to a database Ms. SQL Server
 */
require_once('SystemComponent.php');
class DbConnector extends SystemComponent {
	var $theQuery;
	var $link;
	
	// Function: DbConnector, Purpose: Connect to database
	function __construct() {
		// Load settings from parent class
		$settings = SystemComponent::getSettings();
		
		// Get main setting from the array we just loaded
		$host = $settings['dbhost'];
		$db = $settings['dbname'];
		$user = $settings['dbusername'];
		$pass = $settings['dbpassword'];
		
		// Connect to the database
		$connInfo = array("Database"=>$db, "UID"=>$user, "PWD"=>$pass);
		//------- TEST ERROR HANDLER --------
//		$connstat = sqlsrv_connect($host, $connInfo);
//		if(!$connstat){
//			die("<center><font color=red><BR /><BR /><BR /><BR />MOHON MAAF !!!<BR />DATABASE WEBSITE PUSAT KARANTINA IKAN SEDANG DALAM PERBAIKAN..<BR />MOHON MAKLUM</font></center>");
//			exit;
//		}

//		$this->link = sqlsrv_connect($host, $connInfo) === null ? die("<center><font color=red><BR /><BR /><BR /><BR />MOHON MAAF !!!<BR />DATABASE WEBSITE PUSAT KARANTINA IKAN SEDANG DALAM PERBAIKAN..<BR />MOHON MAKLUM</font></center>") :  sqlsrv_connect($host, $connInfo);
		//----------- END TEST ERROR HANDLER --------

		$this->link = sqlsrv_connect($host, $connInfo) or die("<center><font color=red><BR /><BR /><BR /><BR />MOHON MAAF !!!<BR />DATABASE SEDANG DALAM PROSES OPTIMASI</font></center>");
		register_shutdown_function(array(&$this, 'close'));
	}
	// Function: query, Purpose: Execute a database query
	function query($query) {
		$this->theQuery = $query;
		return sqlsrv_query($this->link, $query);
	}		
	// Function: query, Purpose: Execute a database query
	function queryNumRows($query) {
		$this->theQuery = $query;
		return sqlsrv_query($this->link, $query, array(), array("Scrollable"=>"static"));
	}		
	// Function: getQuery, Purpose: Returns the last database query, for debugging
	function getQuery() {
		return $this->theQuery;
	}
	// Function: getNumRows, Purpose: Return row count, MsSQL version 
	function getNumRows($result){
		return sqlsrv_num_rows($result);
	}
	// Function: hasNumRows, Purpose: Indicates whether the specified statement has rows 
	function hasRows($result){
		return sqlsrv_has_rows($result);
	}
	// Function: affectedNumRows, Purpose: Returns the number of rows modified by the last INSERT, UPDATE, or DELETE query executed 
	function affectedRows($result){
		return sqlsrv_rows_affected($result);
	}
	// Function: fetchArray, Purpose: Get array of query result
	function fetchArray($result) {
		return sqlsrv_fetch_array($result);
	}
    function fetchRow($result) {
		return sqlsrv_fetch_array ($result);
	}
	// Function: fetchObject, Purpose: Get object of query result
	function fetchObject($result) {
		return sqlsrv_fetch_object($result);
	}
	// Function: fetchAssoc, Purpose: Get associative array of query result
	function fetchAssoc($result) {
		return sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	}
	// Function: close, Purpose: Close the connection
	function close() {
		sqlsrv_close($this->link);
	}	
}
?>