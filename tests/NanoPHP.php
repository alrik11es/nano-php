<?php

require '../src/Nano.php';
class NanoPHPTest extends PHPUnit_Framework_TestCase
{
    public function testDbCreate()
    {
       	$nano = new Nano('http://localhost:5984');
       	$nano->db->create('alice', function($err){
       		PHPUnit_Framework_TestCase::assertEquals($err, null, "Failed to create database");
       	});
    }
}