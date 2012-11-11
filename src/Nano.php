<?php
class nano{

	private $config = array();

	public $db;

	function __construct($url){
		$this->config['url'] = $url;
		$this->db = new NanoDB($this);
	}

	function use($db_name){
		$this->config['db'] = $db_name;
		return new NanoDocument($this)
	}
}
