<?php

require 'NanoDB.php';
require 'Relax.php';
//require 'NanoDocument.php';

class nano{

	public $config;
	public $db;
	public $relax;

	function __construct($url){
		$this->config = new stdClass();
		$this->config->url = $url;
		$this->db = new NanoDB($this);
		//$this->relax = new Relax($this);
	}

	function usedb($db_name){
		$this->config->db = $db_name;
		return new NanoDocument($this);
	}
}
