<?php
/*
ACTIONS TESTED
==============

This test will check each possible action indepently from others.
Each action is programmed in the nano-php check if the integration
tests are passing then all the methods checked here should work
wherever you use them.

If there are any unrefered case here feel free to add. 

*/

use \Nano\Nano;

define('DB','http://localhost:5984');

class DBTest extends PHPUnit_Framework_TestCase
{

	public function testDbCreate()
	{
		$nano = new Nano(DB);
		$result = $nano->db->create('alice');
		$this->assertFalse(isset($result->error), "Failed to create DB");
		// Duplicating must be checked
		$result = $nano->db->create('alice');
		$this->assertTrue(isset($result->error), "Db duplicated... WTF?");
	}

	public function testDbDelete()
	{
		$nano = new Nano(DB);
		$result = $nano->db->destroy('alice');
		$this->assertFalse(isset($result->error), "Failed to delete DB");

		$result = $nano->db->destroy('alice');
		$this->assertTrue(isset($result->error), "Can you delete the same DB twice??");
	}

	public function testDbList()
	{
		$nano = new Nano(DB);
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
        $nano = new Nano(DB);
        $nano->db->create('alice');
        $nano->db->create('alien');
    }

	public function testUse(){
		$nano = new Nano(DB);
		$alice = $nano->use('alice');
		$alien = $nano->use('alien');
		$alien->insert(array('character'=>'Queen'));
		$alice->insert(array('character'=>'Rabbit'));
	}

	public function testScope(){
		$nano = new Nano(DB);
		$alice = $nano->scope('alice');
		$alien = $nano->scope('alien');
		$alien->insert(array('character'=>'Rippley'));
		$alice->insert(array('character'=>'The Hatter'));
	}

	public function testInsert(){
		$nano = new Nano(DB);
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
	}

    public function testView()
	{
		$nano = new Nano(DB);
		$alice = $nano->use('alice');

		$alice->insert(array('language'=>'javascript', 'views' => array('list'=>array('map'=>'function(doc) {  if(doc.character != null)   emit(doc.character, doc.character); }'))),'_design/characters');

		$result = $alice->view('characters', 'list');
		//print_r($result);
		$this->assertTrue(isset($result->rows), 'The view cannot be listed.');

		// Show only the Queen of Hearts
		$result = $alice->view('characters', 'list', array('key'=>'Queen of Hearts'));
		$this->assertTrue($result->rows[0]->key == 'Queen of Hearts', 'Something is wrong this action should return the Queen of Hearts');

		$result = $alice->view('characters', 'list', array('skip'=>0,'limit'=>30));
		$this->assertFalse(isset($result->error));
	}

	public function testHead(){
		$nano = new Nano(DB);
		$alien = $nano->use('alien');
		$alien->insert(array('character'=>'Hudson'), 'Hudson');

		$result = $alien->head('Hudson');
		$this->assertTrue(is_string($result->getEtag()), 'Cannot get the Etag');
	}

	public function testCopy(){
		$nano = new Nano(DB);
		$alien = $nano->use('alien');
		$alien->insert(array('character'=>'Rippley'));

		// Normal copy
		$alien->insert(array('language'=>'javascript', 'views' => array('list'=>array('map'=>'function(doc) {  if(doc.character != null)   emit(doc.character, doc); }'))),'_design/characters');

		$result = $alien->view('characters', 'list', array('key'=>'Rippley'));

		$alien->copy($result->rows[0]->value->_id, 'Rippley');

		$rs = $alien->get('Rippley');
		$this->assertFalse(isset($rs->error));

		// With overwrite option
		$rs = $alien->copy($result->rows[0]->value->_id, 'Rippley', array('overwrite'=>true));
		//print_r($rs);
		$this->assertFalse(isset($rs->error));
	}

	public function testGet(){
		// Test if get just one key

		$nano = new Nano(DB);
		$alien = $nano->use('alien');

		$rs = $alien->get('Hudson');

		//$this->assertTrue();
	}

    public static function tearDownAfterClass()
    {
    	$nano = new Nano(DB);
     	$nano->db->destroy('alice');
     	$nano->db->destroy('alien');
    }
}