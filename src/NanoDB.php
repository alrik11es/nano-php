<?php

class NanoDB{

	private $nano;

	function __construct($nano){
		$this->nano = $nano;
	}

	function create($db_name){
		$opts = new stdClass();
		$opts->db = $db_name;
		$opts->method = 'PUT';
		return new Relax($opts, $this->nano);
	}

	function get($db_name){

	}

	/**
	 *	Destroys $db_name
	 **/
	function destroy($db_name){

	}

	/**
	 *	Lists all the databases in couchdb
	 **/
	function list_dbs(){
		$opts = new stdClass();
		$opts->db = "_all_dbs";
		$opts->method = 'GET';

		return new relax($opts, $this->nano);
	}

	function compact($db_name, $design_name){

	}

	function replicate($source, $target, $options = null){

	}

	function changes($db_name, $params = null){

	}

	function follow($db_name, $params = null){

	}

	function usedb($db_name){
		$this->nano->use($db_name);
	}

	function scope($db_name){
		$this->use($db_name);
	}

	function request($options = null){

	}

	function relax($options = null){
		$this->request($options);
	}

	function dinosaur($options = null){
		$this->request($options);
	}
}