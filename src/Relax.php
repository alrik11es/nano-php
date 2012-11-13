<?php

class Relax{

	public $value;
	public $nano;
	
	function __construct($opts, $nano){
		$this->nano = $nano;
		$path = $this->nano->config->url.'/'.$opts->db;
		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $opts->method);
		$this->value = curl_exec($ch);
		curl_close($ch);
	}

	public function exec(){
		// Errors need to be filtered
		return $this->value;
	}
}