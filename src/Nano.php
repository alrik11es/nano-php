<?php

require_once 'NanoDB.php';
require_once 'Relax.php';
require_once 'NanoDocument.php';

class nano{

	public $config;
	public $db;

	function __construct($url){
		$this->config = new stdClass();
		$this->config->url = $url;
		$this->db = new NanoDB($this);
	}

	function use_db($db_name){
		$this->config->db = $db_name;
		$return = new NanoDocument($this);
		return $return;
	}

	// Some reserved words methods are used in nano so here is the solution to implement methods as reserved keywords.
	public function __call($func, $args)
    {
        switch ($func)
        {
            case 'use':
                return $this->use_db((isset($args[0]))? $args[0]: null);
            break;
            default:
                trigger_error("Call to undefined method ".__CLASS__."::$func()", E_USER_ERROR);
            die ();
        }
    }
}
