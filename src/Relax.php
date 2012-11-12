<?php

class Relax{

	public $nano;
	
	function __construct($opts, $nano){
		$this->nano = $nano;
		$path = $this->nano->config->url.'/'.$opts->db;
		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $opts->method);
		$retValue = curl_exec($ch);
	}
}