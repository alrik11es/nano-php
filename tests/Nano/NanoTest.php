<?php
/*
ACTIONS TESTED
==============

- Create DB
- Delete DB
- DB list command
- Use a DB to create a Document
- Inserting a document
- List documents in DB
- Show a view

*/

use \Nano\Nano;

class DBTest extends PHPUnit_Framework_TestCase
{

	public function testDbCreate()
	{
		$nano = new Nano('http://localhost:5984');
		$result = $nano->db->create('alice');
		$this->assertFalse(isset($result->error), "Failed to create DB");
		// Duplicating must be checked
		$result = $nano->db->create('alice');
		$this->assertTrue(isset($result->error), "Db duplicated... WTF?");
	}

	public function testDbDelete()
	{
		$nano = new Nano('http://localhost:5984');
		$result = $nano->db->destroy('alice');
		$this->assertFalse(isset($result->error), "Failed to delete DB");

		$result = $nano->db->destroy('alice');
		$this->assertTrue(isset($result->error), "Can you delete the same DB twice??");
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
		$this->assertInstanceOf('\Nano\NanoDocument', $alice, 'Cannot select alice for DB');
		$result = $alice->insert(array('crazy'=>true), 'rabbit');
		$this->assertTrue(isset($result->ok), 'The crazy rabbit document cannot be created');
		$result = $alice->list();
		$this->assertTrue(isset($result->rows), 'The document cannot be created');

		$alice->insert(array('character'=>'Alice'));
		$alice->insert(array('character'=>'Caterpillar'));
		$alice->insert(array('character'=>'Cheshire Cat'));
		$alice->insert(array('character'=>'Queen of Hearts'));

		$alice->insert(array('language'=>'javascript', 'views' => array('list'=>array('map'=>'function(doc) {  if(doc.character != null)   emit("character", doc.character); }'))),'_design/characters');

		$result = $alice->view('characters', 'list');
		//print_r($result);
		$this->assertTrue(isset($result->rows), 'The view cannot be listed.');

	}

    public static function tearDownAfterClass()
    {
    	$nano = new Nano('http://localhost:5984');
     	$nano->db->destroy('alice');
    }
}