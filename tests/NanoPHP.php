<?php

require 'src/Nano.php';
class DBTest extends PHPUnit_Framework_TestCase
{
	public function testDbCreate()
	{
		$nano = new Nano('http://localhost:5984');
		$result = $nano->db->create('alice');
		$this->assertFalse(isset($result->error), "Failed to create DB");
	}

	public function testDbDelete()
	{
		$nano = new Nano('http://localhost:5984');
		$result = $nano->db->destroy('alice');
		$this->assertFalse(isset($result->error), "Failed to delete DB");
	}

	
	public function testDbList()
	{
		$nano = new Nano('http://localhost:5984');
		$result = $nano->db->list();
		$result = count(json_decode($result->body));
		$this->assertGreaterThan(0, $result);
	}
}

/**
 * @requires DBTest
 */
class DocumentTest extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
    {
        $nano = new Nano('http://localhost:5984');
        $nano->db->create('alice');
    }

    public function testDocumentOperations()
	{
		$nano = new Nano('http://localhost:5984');
		$alice = $nano->use('alice');
		$this->assertInstanceOf('NanoDocument', $alice, 'Cannot select alice for DB');
	}

    public static function tearDownAfterClass()
    {
    	$nano = new Nano('http://localhost:5984');
     	$nano->db->destroy('alice');
    }
}