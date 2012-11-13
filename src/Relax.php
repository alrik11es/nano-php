<?php

class Relax{

	public $value;
	public $nano;
	
	function __construct($opts, $nano){

		$this->nano = $nano;

		// If DB is not selected the URL may contain the DB
		$path = $this->nano->config->url.'/'.$opts->db;

		// This will select the path depending on what are you going to send
		if(isset($opts->path)){
			$path .= '/'.$opts->path;
		} else if(isset($opts->doc)){
			// not a design document
			if(!preg_match('/^_design/', $opts->doc)){
				$path .= '/'.urlencode(json_encode($opts->doc));
			} else {
				// design document
				$path .= '/'.$opts->doc;
			}
	      	
	      	if(isset($opts->doc)){
	      		$path .= '/'.$opts->att;
  		 	}
		}

		$ch = curl_init($path);

    	curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    	// If there is a body we must send that body
    	if(isset($opts->body)){
			//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, json_encode($opts->body));
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($opts->body)); 
    	}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
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