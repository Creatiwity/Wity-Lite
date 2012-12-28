<?php 
/**
 * WDatabase.php
 */

defined('IN_WITY') or die('Access denied');

/**
 * WDatabase manages all database interactions
 *
 * @package WCore
 * @author Johan Dufau <johandufau@gmail.com>
 * @version 0.3-22-11-2012
 */
class WDatabase extends PDO {
    
    /**
     *
     * @var string stores table prefix that is used in the database 
     */
	private $tablePrefix = "";
    
    /**
     *
     * @var array(string) list of all tables that will be automatically prefixed 
     */
	private $tables = array();
	
    /**
     * Opens the PDO connection with the database
     * 
     * @param string $dsn database server
     * @param string $user username
     * @param string $password password
     * @throws Exception
     */
	public function __construct($dsn, $user, $password) {
		if (!class_exists('PDO')) {
			throw new Exception("WSystem::__construct(): Class PDO not found.");
		}
		
		try {
			# Bug de PHP5.3 : constante PDO::MYSQL_ATTR_INIT_COMMAND n'existe pas
			@parent::__construct($dsn, $user, $password);
		} catch (PDOException $e) {
			WNote::error('sql_conn_error', "Impossible to connect to MySQL.<br />".utf8_encode($e->getMessage()), 'custom');
			die;
		}
		$this->tablePrefix = WConfig::get('database.prefix');
	}
	
	/**
	 * Declare a new table in order to be automaticly prefixed
	 * 
	 * @param string $table table's name
	 */
	public function declareTable($table) {
		$this->tables[] = $table;
	}
	
    /**
     * Transforms a query into another query with prefixed tables
     * 
     * @param type $querystring query without prefix
     * @return string query with all tables contained in the $table private property prefixed
     */
	private function prefixTables($querystring) {
		if (!empty($this->tablePrefix)) {
			foreach ($this->tables as $table) {
				$querystring = preg_replace('#([^a-z0-9_])'.$table.'([^a-z0-9_])#', '$1'.$this->tablePrefix.$table.'$2', $querystring);
			}
		}
		return $querystring;
	}
	
    /**
     * Executes the query and returns the response
     * 
     * @param string $querystring
     * @return PDOStatement|false a PDOStatement object or false if an error occurs
     */
	public function query($querystring) {
		return parent::query($this->prefixTables($querystring));
	}
	
    /**
     * Prepares a statement for execution and returns a statement object
     * 
     * @todo Catch the eventual exception throwed by PDO::prepare
     * 
     * @param string $querystring the query that will be prepared
     * @param string $driver_options optional list of key=>value pairs
     * @return PDOStatement|false a PDOStatement object or false if an error occurs
     */
	public function prepare($querystring, $driver_options = array()) {
		return parent::prepare($this->prefixTables($querystring), $driver_options);
	}
}

?>