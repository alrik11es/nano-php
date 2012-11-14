<?php
/*
ACTIONS TESTED
==============

- Create DB
- Create multiple DBs
- Delete DB
- DB list command
- Use a DB to create a Document
- Inserting a document
- List documents in DB

*/


require 'src/Nano.php';
class DBTest extends PHPUnit_Framework_TestCase
{
	public function testDbCreate()
	{
		$nano = new Nano('http://localhost:5984');
		$result = $nano->db->create('alice');
		$this->assertFalse(isset($result->error), "Failed to create DB");
		$result = $nano->db->create(array('error','database'));
		$this->assertTrue(isset($result->error), 'Multiple DB creation not supported');
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
		$result = count($result);
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
		$result = $alice->insert(array('crazy'=>true), 'rabbit');
		$this->assertTrue(isset($result->ok), 'The document cannot be created');
		$result = $alice->list();
		$this->assertTrue(isset($result->rows), 'The document cannot be created');
	}

    public static function tearDownAfterClass()
    {
    	$nano = new Nano('http://localhost:5984');
     	$nano->db->destroy('alice');
    }
}