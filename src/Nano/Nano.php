<?php


namespace Nano;

require_once __DIR__ . '/NanoDocument.php';
require_once __DIR__ . '/NanoDB.php';
require_once __DIR__ . '/Relax.php';
require_once __DIR__ . '/OptionsClass.php';


class Nano{

	public $config;
	public $db;

	function __construct($url){
		$this->config = new \stdClass();
		$this->config->url = $url;
		$this->db = new NanoDB($this);
	}

	function use_db($db_name){
		$this->config->db = $db_name;
		$return = new NanoDocument($this);
		return $return;
	}

	// Some reserved words methods are used in nano so here is the solution to implement methods as reserved keywords.
	public function __call($func, $args)
    {
        switch ($func)
        {
            case ('use' || 'scope'):
                return $this->use_db((isset($args[0]))? $args[0]: null);
            break;
            default:
                trigger_error("Call to undefined method ".__CLASS__."::$func()", E_USER_ERROR);
            die ();
        }
    }

    static function arrayToObject($array, $class = 'stdClass', $strict = false)
    {
        if (!is_array($array)) {
            return $array;
        }

        //create an instance of an class without calling class's constructor
        $object = unserialize(
            sprintf(
                'O:%d:"%s":0:{}', strlen($class), $class
            )
        );

        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {

                    if (method_exists($object, 'set' . $name)) {
                        $object->{'set' . $name}(Nano::arrayToObject($value));
                    } else {
                        if (($strict)) {

                            if (property_exists($class, $name)) {

                                $object->$name = Nano::arrayToObject($value);

                            }

                        } else {
                            $object->$name = Nano::arrayToObject($value);
                        }

                    }

                }
            }
            return $object;
        } else {
            return FALSE;
        }
	}
}
