<?php
App::uses('CousineLocal', 'Model');

/**
 * CousineLocal Test Case
 *
 */
class CousineLocalTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.cousine_local',
		'app.cousine'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CousineLocal = ClassRegistry::init('CousineLocal');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CousineLocal);

		parent::tearDown();
	}

}
