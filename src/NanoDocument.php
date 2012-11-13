<?php

class NanoDocument{

	private $nano;

	function __construct($nano){
		$this->nano = $nano;
	}

	function insert($doc, $params = false){
		//var opts = {db: db_name, body: doc, method: "POST"};

		$opts = new OptionsClass();
		$opts->db = $this->nano->config->db;
		$opts->body = $doc;
		$opts->method = 'POST';
		$opts->params = new stdClass();

		if(is_array($params)){
			$params = Nano::arrayToObject($params);
			if(!$params)
				trigger_error("A string, multidimensional array or an object in params only", E_USER_ERROR);
		}

		if(is_string($params)){
			$opts->params->doc_name = $params;
		}

		if ($params) {
			if(isset($opts->params->doc_name)) {
				$opts->doc = $opts->params->doc_name;
				$opts->method = "PUT";
			}
			$opts->params = $params;
		}

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}
	
	function destroy(){

	}
	
	function get(){}
	function head(){}
	function copy(){}
	function bulk(){}
	function list_doc(){}
	function fetch(){}

	// Views
	function view(){}
	function show(){}
	function atomic(){}


	// Some reserved words methods are used in nano so here is the solution to implement methods as reserved keywords.
	public function __call($func, $args)
    {
        switch ($func)
        {
            case 'list':
                return $this->list_doc((isset($args[0]))? $args[0]: null);
            break;
            default:
                trigger_error("Call to undefined method ".__CLASS__."::$func()", E_USER_ERROR);
            die ();
        }
    }
}