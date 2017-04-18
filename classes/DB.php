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
    /**
    * Get an instance of the Database
    *
    * @return Instance
    */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
    * Constructor
    *
    */
    private function __construct()
    {
        $this->_config = Config::get('config/database');
        try {
            $this->_connection = new PDO($this->_config['driver'].':host='.$this->_config[$this->_config['driver']]['host'].';dbname='.$this->_config[$this->_config['driver']]['db'], $this->_config[$this->_config['driver']]['user'], $this->_config[$this->_config['driver']]['pass']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    /**
    * Magic method clone is empty to prevent duplication of connection
    *
    */
    private function __clone(){}
    /**
    * PDO connection
    *
    * @return Connection
    */
    public function getConnection()
    {
        return $this->_connection;
    }
    /**
     * Create database querie.
     *
     * @param  string  $sql
     * @param  array  $params
     * @return DB Object
     */
    public function query($sql, $params = array())
    {
        $this->_error = false;
        if($this->_query = $this->_connection->prepare($sql)) {
            $x = 1;
            if(!empty($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x,$param);
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
    /**
     * Run database querie.
     *
     * @param  string  $action
     * @param  string  $table
     * @param  array  $where
     * @return DB Object
     */
     private function action($action, $table, $where = array())
     {
         if(count($where) === 3) {
             $operators = array('=', '<', '>', '<=', '>=');
             $field     = $where[0];
             $operator  = $where[1];
             $value     = $where[2];
             if(in_array($operator, $operators)) {
                 $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                 if(!$this->query($sql, array($value))->error()) {
                     return $this;
                 }
             }
         } else {
             $sql = "{$action} FROM {$table}";
             if(!$this->query($sql)->error()) {
                 return $this;
             }
         }
         return false;
     }
     /**
      * Run SELECTE querie.
      *
      * @param  string  $attr
      * @param  string  $table
      * @param  array  $where
      * @return DB Object
      */
      public function get($field, $table, $where = array())
      {
          return $this->action("SELECT {$field}", $table, $where);
      }
      /**
       * Select data by id.
       *
       * @param  int  $id
       * @param  string  $table
       * @return DB Object
       */
       public function find($id, $table)
       {
           return $this->action("SELECT * ", $table, array('id', '=', $id));
       }
      /**
       * Run DELETE querie.
       *
       * @param  string  $table
       * @param  array  $where
       * @return DB Object
       */
       public function delete($table, $where)
       {
           return $this->action("DELETE", $table, $where);
       }
       /**
        * Insert new data.
        *
        * @param  string  $table
        * @param  array  $fields
        * @return Bool
        */
        public function insert($table, $fields)
        {
            $keys = implode(',',array_keys($fields));
            $fields_num = count($fields);
            $values = '';
            $x = 1;
            foreach($fields as $field) {
                $values .= '?';
                if($x < $fields_num) {
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
       /**
        * Update data.
        *
        * @param  string  $table
        * @param  int  $id
        * @param  array  $fields
        * @return DB Object
        */
        public function update($table, $id, $fields)
        {
            $fields_num = count($fields);
            $set = '';
            $x = 1;
            foreach($fields as $key => $value) {
                $set .= "{$key} = ?";
                if($x < $fields_num) {
                    $set .= ', ';
                }
                $x++;
             }
             //die($set);
             $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
             if(!$this->query($sql, $fields)->error()) {
                 return true;
             }
             return false;
         }
       /**
        * Retunrn all records as object.
        *
        * @return Object data
        */
        public function results()
        {
            return $this->_results;
        }
        /**
         * Retunrn first record as object.
         *
         * @return Object data
         */
         public function first()
         {
             return $this->results()[0];
         }
       /**
        * Check for errors.
        *
        * @return Bool
        */
        public function error()
        {
            return $this->_error;
        }
       /**
        * Count records.
        *
        * @return Int
        */
        public function count()
        {
            return $this->_count;
        }
}