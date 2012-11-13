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
		$this->original_message = $this->value;
		$this->value = json_decode($this->value);
		$this->headers = curl_getinfo($ch);
		curl_close($ch);
	}

	public function exec(){

		

		$return = new stdClass();

		if(isset($this->value->error))
			$return->error = $this->value->error;

		$return->body = $this->original_message;
		$return->header = 'Deactivate'; //$this->headers;

		return $return;
		// Errors need to be filtered
		//return array($this->value);
	}
}