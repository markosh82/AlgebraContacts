<?php

class DB
{
	private static $_instance = null;
	private $_config;
	private $_connection;
	private $_query;
	private $_error = false;
	private $_results;
	private $_count = 0;
	
	private function __clone(){}
		
public static function getInstance()
{
	
if (!self::$_instance)
{
	
self::$_instance = new self();
}
	return self::$_instance;
}
	
private function __construct()
{
	$this->_config = Config::get('config/database');
	
	try {
		
		$this->_connection = new PDO($this->_config['driver'].
		':host='.$this->_config[$this->_config['driver']]['host'].';dbname='.$this->_config[$this->_config['driver']]['db'].
		';charset=UTF8',$this->_config[$this->_config['driver']]['user'],$this->_config[$this->_config['driver']]['pass']);
		
		} catch (PDOException $e) {
		die($e->getMessage());
		}
			
}	
	public function query($sql, $params = array())
	{
		$this->_error = false;
		
		if($this->_query = $this->_connection->prepare($sql
		)) {
			$x = 1;
			if(!empty($params)) {
				foreach($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll($this->_config['fetch']);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
			
		}
		return $this;
	}
	
	private function action($action, $table, $where = array()){
		
		if (!empty($where)) {
			$where_split = array_chunk($where, 4);
			$fields_num = count($where_split);
			$cond = "";
			$x = 1;
			foreach ($where_split as $condition) {
				$values[] = $condition[2];
				$cond .= $condition[0]." ".$condition[1]." ? ";
				if ($x < $fields_num) {
					$cond .= $condition[3]." ";
				}
				$x++;
			}
			//	echo '<h3>'. $cond .'</h3>';
			$sql = "{$action} FROM {$table} WHERE {$cond}";
			if (!$this->query($sql, $values)->error()) {
				return $this;
			}
		} else{
			$sql = "{$action} FROM {$table}";
			if (!$this->query($sql)->error()) {
				return $this;
			}
		}
	}
	
	
	public function get($field, $table, $where = array())
	{ 
		return $this->action("SELECT {$field}", $table, $where);
	}
	
	public function insert($table, $fields)
	{
		$keys = implode(',',array_keys($fields));
		$fields_num = count($fields);
		$values = '';
		$x = 1;
		
		foreach ($fields as $field) {
			$values .= '?';
			if ($x < $fields_num) {
				$values .= ',';
			}
			$x++;
		}
		//die($values);
		$sql = "INSERT INTO {$table} ({$keys}) VALUES ({$values})";
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}
	
	public function update($table, $id, $fields) 
	{
		$fields_num = count($fields);
		$set = '';
		$x = 1;
		
		foreach($fields as $key => $field) {
			$set .= "{$key} = ?";
			if($x < $fields_num) {
				$set .= ', ';
			}
			$x++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id={$id}";
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}
	
	public function delete($table, $where)
	{
		return $this->action('DELETE', $table, $where);
	}
	
	
	public function getConnection()
	{
		return $this->_connection;	
	}
	
	public function error()
	{
		return $this->_error;
	}
	public function results()
	{
		return $this->_results;
	}
	
	public function first() 
	{
		return $this->_results[0];
	}
		
	
	public function count()
	{
		return $this->_count;
	}
}