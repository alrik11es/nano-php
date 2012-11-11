<?php

class NanoDB{

	private $nano;

	function __construct($nano){
		$this->nano = $nano;
	}

	function create($db_name, $callback){
		return relax('{db: '.$db_name.', method: "PUT"}', $callback);
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
	function listdb(){

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