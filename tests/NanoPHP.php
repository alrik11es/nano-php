<?php

require 'src/Nano.php';
class Test extends PHPUnit_Framework_TestCase
{
    public function testDbCreate()
    {
       	$nano = new Nano('http://localhost:5984');
       	$result = $nano->db->create('alice');
       	$result = json_decode($result);
       	$this->assertEquals($result->ok, true, "Failed to create DB");
    }

    public function testDbDelete()
    {
       	$nano = new Nano('http://localhost:5984');
       	$result = $nano->db->destroy('alice');
       	$result = json_decode($result);
       	$this->assertEquals($result->ok, true, "Failed to delete DB");
    }

    public function testDbList()
    {
        $nano = new Nano('http://localhost:5984');
        $result = $nano->db->list();
        $result = count(json_decode($result));
        $this->assertGreaterThan(0, $result);
    }
}