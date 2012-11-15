<?php
class NanoDB{

	private $nano;

	function __construct($nano){
		$this->nano = $nano;
	}

	function create($db_name){

		if(!is_string($db_name))
			trigger_error("Multiple databases at same time not allowed for creation. (String needed)", E_USER_ERROR);

		$opts = new OptionsClass();
		$opts->db = $db_name;
		$opts->method = 'PUT';
		
		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}

	function get($db_name){

		if(!is_string($db_name))
			trigger_error("You can only get info of one DB. (String needed)", E_USER_ERROR);

		$opts = new OptionsClass();
		$opts->db = $db_name;
		$opts->method = 'GET';

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}

	/**
	 *	Destroys $db_name
	 **/
	function destroy($db_name){

		if(!is_string($db_name))
			trigger_error("You cannot destroy multiple databases with the same command. (String needed)", E_USER_ERROR);

		$opts = new OptionsClass();
		$opts->db = $db_name;
		$opts->method = 'DELETE';

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}

	/**
	 *	Lists all the databases in couchdb
	 **/
	function list_dbs(){
		$opts = new OptionsClass();
		$opts->db = "_all_dbs";
		$opts->method = 'GET';

		$relax = new Relax($opts, $this->nano);
		return $relax->exec();
	}

	function compact($db_name, $design_name){

	}

	function replicate($source, $target, $options = null){

	}

	function changes($db_name, $params = null){

	}

	function follow($db_name, $params = null){

	}

	/*function use_db($db_name){
		$this->nano->use_db($db_name);
	}

	function scope($db_name){
		$this->use_db($db_name);
	}*/

	function request($options = null){

	}

	function relax($options = null){
		$this->request($options);
	}

	function dinosaur($options = null){
		$this->request($options);
	}

	// Some reserved words methods are used in nano so here is the solution to implement methods as reserved keywords.
	public function __call($func, $args)
    {
        switch ($func)
        {
            case 'list':
                return $this->list_dbs();
            break;
            case ('use' || 'scope'):
                return $this->nano->use_db((isset($args[0]))? $args[0]: null);
            break;
            default:
                trigger_error("Call to undefined method ".__CLASS__."::$func()", E_USER_ERROR);
            die ();
        }
    }
}