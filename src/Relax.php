<?php

class Relax{

	public $value;
	public $nano;
	
	function __construct(OptionsClass $opts, $nano){

		/*echo '<pre>';
		print_r($opts);
		echo '</pre>';*/

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

		if(isset($opts->params) && is_object($opts->params)){
			foreach($opts->params as $key => $value){
				//params[key] = JSON.stringify(params[key]);
			}
		}

		$ch = curl_init($path);

    	curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    	// If there is a body we must send that body
    	if(isset($opts->body)){
			//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, json_encode($opts->body));
			if(is_object($opts->body) || is_array($opts->body))
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($opts->body)); 
			else
				trigger_error("The document can be only an object or array", E_USER_ERROR);
    	}

    	// Fiddler debug line
    	//curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $opts->method);

		$this->value = curl_exec($ch);
		$this->original_message = $this->value;
		$this->value = json_decode($this->value);
		$this->headers = curl_getinfo($ch);

		curl_close($ch);
	}

	public function exec(){
		$return = json_decode($this->original_message);

		return $return;
	}
}