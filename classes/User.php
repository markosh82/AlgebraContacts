<?php

class User
{
    private $_db;
    private $_config;
    private $_data;
    private $_sessionName;
	private $_cookieName;
    private $_isLoggedIn;
	
    public function __construct($user = null)
    {
        $this->_config = Config::get('config/session');
        $this->_sessionName = $this->_config['sessions']['session_name'];
		$this->_cookieName = $this->_config['remember']['cookie_name'];
        $this->_db = DB::getInstance();
		
        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //logout
                }
            }
        } else {
            $this->find($user);
        }
    }
    public function create($fields = array())
    {
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception ('There was a problem creating an account.');
        }
    }
    public function find($user = null)
    {
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('*', 'users', array($field, '=', $user));
			
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    public function login($username = null, $password = null, $remember = false)
    {
        $user = $this->find($username);
        if($user) {
            if($this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->_sessionName, $this->data()->id);
				
				if($remember) {
					$hash = Hash::unique();
					//$hashCheck = $this->_db->get('users', array('id', '=', $this->data()->id));
					
					
					
					/*if(!$hashCheck->count()) {
						$this->_db->insert('users', array(
							'id'   => $this->data()->id,
							'hash' => $hash
						));
					} else {
						$hash = $hashCheck->first()->hash; */
					}
				Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
				}
				
				
                return true;
            }
        }
        return false;
    }
	
    public function logout() {
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}
    public function data()
    {
        return $this->_data;
    }
    public function check()
    {
        return $this->_isLoggedIn;
    }
}