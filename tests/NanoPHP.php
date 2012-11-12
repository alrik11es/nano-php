<?php

require '../src/Nano.php';
class NanoPHPTest extends PHPUnit_Framework_TestCase
{
    public function testDbCreate()
    {
       	$nano = new Nano('http://localhost:5984');
       	$err = $nano->db->create('alice');
       	$this->assertEquals($err, null, "Failed to create database");
    }
}