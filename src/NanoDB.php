<?php

class NanoDB{

	function __construct(){
		
	}

	function create($db_name){
		return true;
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
	function list(){

	}

	function compact($db_name, $design_name){

	}

	function compact($source, $target, $options = null){

	}

	function changes($db_name, $params = null){

	}

	function follow($db_name, $params = null){

	}

	function use($db_name){
		$this->config['db'] = $db_name;
		return new NanoDocument($this)
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