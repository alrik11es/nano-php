<?php

class NanoDocument{

	private $nano;

	function __construct($nano){
		$this->nano = $nano;
	}

	function insert($doc, $params){
		//var opts = {db: db_name, body: doc, method: "POST"};

		$opts = new stdClass();
		$opts->db = $this->nano->config->db;
		$opts->doc_name = $params;
		$opts->method = 'POST';

		if($params === "string"){
			$opts->doc_name = $params;
		} else if ($params) {
			if($params->doc_name) {
				$opts->doc = $params->doc_name;
				$opts->method = "PUT";
				unset($params->doc_name);
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