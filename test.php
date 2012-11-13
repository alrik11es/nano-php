<?php

require 'src/Nano.php';

$nano = new Nano('http://localhost:5984');


//$err = $nano->db->create('alice');

//$nano->db->create('alice');
//$nano->db->destroy('alice');
//$nano->db->list();

$alice = $nano->db->use('alice');


$result = $alice->insert(array('crazy'=>false, 'chineese' => true), 'rabbit');
print_r($result);
