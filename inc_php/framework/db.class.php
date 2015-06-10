<?php


defined('_JEXEC') or die('Restricted access');

	
	class UniteGalleryDB{
		
		private $pdb;
		private $lastRowID;
		
		/**
		 * 
		 * constructor - set database object
		 */
		public function __construct(){
			$this->pdb = new UniteProviderDBUG();
		}
		
		/**
		 * 
		 * throw error
		 */
		private function throwError($message,$code=-1){
			UniteFunctionsUG::throwError($message,$code);
		}
		

		/**
		 * validate for errors
		 * @param unknown_type $prefix
		 */
		private function checkForErrors($prefix = ""){

			$message = $this->pdb->getErrorMsg();
						
			if(empty($message))
				return(false);
			
			if(!empty($prefix))
				$message = $prefix." ".$message;
			
			$errorNum = $this->pdb->getErrorNum();
			
			$this->throwError($message, $errorNum);
		}
		
		
		/**
		 * 
		 * insert variables to some table
		 */
		public function insert($tableName, $arrItems){
			
			if(method_exists($this->pdb, "insert")){
				$this->lastRowID = $this->pdb->insert($tableName, $arrItems);
				return($this->lastRowID);
			}
			
			
			$strFields = "";
			$strValues = "";
			foreach($arrItems as $field=>$value){
				$value = "'".$this->escape($value)."'";
				if($field == "id") continue;
				if($strFields != "") $strFields .= ",";
				if($strValues != "") $strValues .= ",";
				$strFields .= $field;
				$strValues .= $value;
			}
			
			$insertQuery = "insert into $tableName($strFields) values($strValues)";									
			
			$this->runSql($insertQuery,"insert");
			$this->lastRowID = $this->pdb->insertid();
			
			return($this->lastRowID);
		}
		
		
		/**
		 * 
		 * get last insert id
		 */
		public function getLastInsertID(){
			$this->lastRowID = $this->pdb->insertid();
			return($this->lastRowID);			
		}
		
		
		/**
		 * 
		 * delete rows
		 */
		public function delete($table,$where){
			
			UniteFunctionsUG::validateNotEmpty($table,"table name");
			UniteFunctionsUG::validateNotEmpty($where,"where");
			
			if(method_exists($this->pdb, "delete")){
				$numRows = $this->pdb->delete($table, $where);
				return($numRows);
			}
			
			if(is_array($where))
				$where = $this->getWhereString($where);
			
			$query = "delete from $table where $where";
			
			$success = $this->runSql($query, "delete error");
			return($success);
		}
		
		
		/**
		 * 
		 * get where string from where array
		 */
		private function getWhereString($where){
			$where_format = null;
			
			foreach ( $where as $key=>$value ) {
				if(is_numeric($value) == false)
					$value = "'$value'";
					
				$wheres[] = "$key = {$value}";
			}
			
			$strWhere = implode( ' AND ', $wheres );
			return($strWhere);
		}
		
		
		/**
		 * 
		 * insert variables to some table
		 */
		public function update($tableName,$arrData,$where){
			
			if(method_exists($this->pdb, "update")){
				
				$numRows = $this->pdb->update($tableName, $arrData, $where);
				
				return($numRows);
			}
			
			UniteFunctionsUG::validateNotEmpty($tableName,"table cannot be empty");
			UniteFunctionsUG::validateNotEmpty($where,"where cannot be empty");
			
			if(is_array($where))
				$where = $this->getWhereString($where);
			
			$strFields = "";
			foreach($arrData as $field=>$value){
				$value = "'".$this->escape($value)."'";
				if($strFields != "") $strFields .= ",";
				$strFields .= "$field=$value";
			}
									
			$updateQuery = "update $tableName set $strFields where $where";
						
			//$updateQuery = "update #__revslider_css set params='{\"font-size\":\"50px\",\"line-height\":\"67px\",\"font-weight\":\"700\",\"font-family\":\"\'Roboto\', sans-serif\",\"color\":\"#ffffff\",\"text-decoration\":\"none\",\"background-color\":\"rgba(141, 68, 173, 0.65)\",\"padding\":\"0px 15px 5px 15px\",\"border-width\":\"0px\",\"border-color\":\"rgb(34, 34, 34)\",\"border-style\":\"none\"}',hover='\"\"',settings='{\"hover\":\"false\"}' where handle = '.tp-caption.roboto'";
						
			$numRows = $this->runSql($updateQuery, "update error");
			
			//dmp($updateQuery);dmp($numRows);exit();			
			
			return($numRows);
		}
		
		
			/**
		 * 
		 * run some sql query
		 */
		public function runSql($query){
						
			$response = $this->pdb->query($query);
															
			$this->checkForErrors("Regular query error");
						
			return($response);
		}
				
		
		/**
		 * 
		 * fetch rows from sql query
		 */
		public function fetchSql($query){
			
			$this->checkForErrors("fetch");
			
			$rows = $this->pdb->fetchSql($query);
			
			$rows = UniteFunctionsUG::convertStdClassToArray($rows);
			
			return($rows);
		}
		
		/**
		 * 
		 * get row wp emulator
		 */
		public function get_row($query = null){
			
			$rows = $this->pdb->fetchSql($query);
						
			$this->checkForErrors("get_row");
			
			if(count($rows) == 1)
				$result = $rows[0];
			else
				$result = $rows;
			
			return($result);
		}
		
		/**
		 * 
		 * get data array from the database
		 * 
		 */
		public function fetch($tableName,$where="",$orderField="",$groupByField="",$sqlAddon=""){
			
			$query = "select * from $tableName";
			if($where) $query .= " where $where";
			if($orderField){
				$orderField = $this->escape($orderField);
				$query .= " order by $orderField";
			}
			if($groupByField) $query .= " group by $groupByField";
			if($sqlAddon) $query .= " ".$sqlAddon;
						
			$rows = $this->fetchSql($query);
						
			return($rows);
		}
		
		/**
		 * 
		 * fetch only one item. if not found - throw error
		 */
		public function fetchSingle($tableName,$where="",$orderField="",$groupByField="",$sqlAddon=""){
			$response = $this->fetch($tableName, $where, $orderField, $groupByField, $sqlAddon);
			if(empty($response))
				$this->throwError("Record not found");
			$record = $response[0];
			return($record);
		}
		
		/**
		 * 
		 * escape data to avoid sql errors and injections.
		 */
		public function escape($string){
			$newString = $this->pdb->escape($string);
			return($newString);
		}
		
		
	}
	
?>