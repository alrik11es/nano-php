<?php
class nano{

	private $config = array();

	public $db;

	function __construct($url){
		$this->config['url'] = $url;
		$this->db = new NanoDB($this);
	}
}
