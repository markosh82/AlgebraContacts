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
		':host='.$this->_config[$this->_config['driver']]['host'].';dbname='.$this->_config[$this->_config['driver']]['db'],
		$this->_config[$this->_config['driver']]['user'],$this->_config[$this->_config['driver']]['pass']);
		
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
	
	public function get()
	{
		
	}
	
	public function insert()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
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
	public function count()
	{
		return $this->_count;
	}
}