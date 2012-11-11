<?php

require 'NanoDB.php';


class nano{

	private $config = stdClass();
	public $db;
	public $relax;

	function __construct($url){
		$this->config->url = $url;
		$this->db = new NanoDB($this);
		$this->relax = new Relax($this);
	}

	function usedb($db_name){
		$this->config->db = $db_name;
		return new NanoDocument($this);
	}
}
