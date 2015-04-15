<?php

defined('_JEXEC') or die('Restricted access');

class UniteProviderDBUG{
	
	private $wpdb;
	
	/**
	 *
	 * constructor - set database object
	 */
	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	
	/**
	 * get error number
	 */
	public function getErrorNum(){
		return mysql_errno();
	}
	
	
	/**
	 * get error message
	 */
	public function getErrorMsg(){
		return $this->wpdb->last_error;
	}
	
	/**
	 * get last row insert id
	 */
	public function insertid(){
		return $this->wpdb->insert_id;
	}
	
	
	/**
	 * do sql query, return success
	 */
	public function query($query){
		
		$this->wpdb->suppress_errors(false);
		
		$success = $this->wpdb->query($query);
		return($success);
	}
	
	
	/**
	 * get affected rows after operation
	 */
	public function getAffectedRows(){
		return $this->wpdb->num_rows;
	}
	
	/**
	 * fetch objects from some sql
	 */
	public function fetchSql($query){
		
		$this->wpdb->suppress_errors(false);
		
		$rows = $this->wpdb->get_results($query, ARRAY_A);
				
		return($rows);
	}
	
	/**
	 * escape some string
	 */
	public function escape($string){
		return $this->wpdb->_escape($string);
	}
	
}



?>