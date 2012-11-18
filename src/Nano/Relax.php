<?php
namespace Nano;

class Relax{

	public $value;
	public $nano;
	
	function __construct(OptionsClass $opts, $nano){

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

		// This maps the params that you request adding them to the query
		if(isset($opts->params) && (is_object($opts->params) || is_array($opts->params))){
			$path .= '?';
			foreach($opts->params as $key => $value){
				$path .= $key.'='.urlencode(json_encode($value));
			}
		}

		// This is the Curl init where we add the path to the action we are going to do.
		$ch = curl_init($path);
		// We send the data as POST
    	curl_setopt($ch, CURLOPT_POST, 1);
    	// We add a empty body to avoid -1 lenght in the query.
		curl_setopt($ch, CURLOPT_POSTFIELDS, '');

    	// If there is a body we must send that body
    	if(isset($opts->body)){
			// The real body will be generated here if necessary
			if(is_object($opts->body) || is_array($opts->body))
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($opts->body));
			else
				trigger_error("The document can be only an object or array", E_USER_ERROR);
    	}

    	// Fiddler debug line
    	//curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

    	// This is the type of the transfer
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		// We want to parse the return of the server data so true
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// This is the REQUEST -> PUT, GET, DELETE...
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $opts->method);

		$this->value = curl_exec($ch);
		$this->original_message = $this->value;
		$this->value = json_decode($this->value);
		$this->headers = curl_getinfo($ch);

		curl_close($ch);
	}

	public function exec(){
		// This will map the result as PHP object so you can work directly with it (Just like in .js)
		$return = json_decode($this->original_message);
		return $return;
	}
}