<?php

require '../src/Nano.php';

$nano = new Nano('http://localhost:5984');
//$err = $nano->db->create('alice');

$nano->db->list_dbs();
