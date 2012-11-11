<?php

class Relax{
	
	function __construct(){

		$ch = curl_init($this->_serviceUrl . $id);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		//curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));

	}
}