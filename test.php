<?php

require 'src/Nano.php';

$nano = new Nano('http://localhost:5984');


//$err = $nano->db->create('alice');

$nano->db->create('alice');
//
//$nano->db->list();

$alice = $nano->db->use('alice');


$result = $alice->insert(array('crazy'=>false, 'chineese' => true), 'rabbit');




//$result = $alice->insert(array('crazy'=>true, '_rev'=>''), 'rabbit');

$result = $alice->list();

print_r($result);

$nano->db->destroy('alice');