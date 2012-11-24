<?php

namespace Nano;

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

		if(is_array($params)){
			$params = Nano::arrayToObject($params);
			if(!$params)
				trigger_error("A string, multidimensional array or an object in params only", E_USER_ERROR);
		}

		if(is_string($params)){
			$opts->params = new \stdClass();
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
	
	function destroy($doc_name, $rev){

		$opts = new OptionsClass();
		$opts->db = $this->nano->config->db;
		$opts->doc = $doc_name;
		$opts->method = 'DELETE';
		$opts->params = new \stdClass();
		$opts->params->rev = $rev;

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}
	
	function get($doc_name, $params = false){
		//return relax({ db: db_name, doc: doc_name, method: "GET", params: params }, callback);
		$opts = new OptionsClass();
		$opts->db = $this->nano->config->db;
		$opts->doc = $doc_name;
		$opts->method = 'GET';
		if($params)
			$opts->params = $params;

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}

	function head($doc_name){
		$opts = new OptionsClass();
		$opts->db = $this->nano->config->db;
		$opts->doc = $doc_name;
		$opts->method = 'HEAD'; // Here the method should be HEAD but It's to slow dont know why...

		$relax = new Relax($opts, $this->nano);
		$return = $relax->exec();

		return $relax->response;
	}

	function copy($doc_src, $doc_dest, $opts = false){

		$opts_r = new OptionsClass();
		$opts_r->db = $this->nano->config->db;
		$opts_r->doc = $doc_src;
		$opts_r->method = 'COPY';
		$opts_r->headers = array('Destination'=>$doc_dest);

		if($opts){
			if(is_array($opts))
				$opts = Nano::arrayToObject($opts);

			if($opts->overwrite){
				$r = $this->head($doc_dest);
				$opts_r->headers['Destination'] .= '?rev='.str_replace('"','', $r->getEtag());
			}
		}

		$relax = new Relax($opts_r, $this->nano);
		return $relax->exec();

	}

	function bulk(){}

	function list_docs($params = false){
		$opts = new OptionsClass();
		$opts->db = $this->nano->config->db;
		$opts->path = '_all_docs/';
		if($params)
			$opts->params = $params;
		$opts->method = 'GET';

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}

	function fetch(){}

	// Views
	function view($design_name, $view_name, $params = false){

		$opts = new OptionsClass();
		$opts->db = $this->nano->config->db;
		$opts->path = '_design/'.$design_name.'/_view/'.$view_name;
		$opts->params = $params;

		if(isset($params->keys) && $params){
			$opts->body = array('keys'=>$params->keys); // {keys: params.keys}
			unset($opts->params->keys);
			$opts->method = 'POST';

			$relax = new Relax($opts, $this->nano);
			return $relax->exec();
		} else {
			$opts->method = 'GET';
			$relax = new Relax($opts, $this->nano);
			return $relax->exec();
		}

	}

	function show($design_name, $show_fn_name, $docId, $params){}

	function atomic(){}


	// Some reserved words methods are used in nano so here is the solution to implement methods as reserved keywords.
	public function __call($func, $args)
    {
        switch ($func)
        {
            case 'list':
                return $this->list_docs((isset($args[0]))? $args[0]: null);
            break;
            default:
                trigger_error("Call to undefined method ".__CLASS__."::$func()", E_USER_ERROR);
            die ();
        }
    }
}