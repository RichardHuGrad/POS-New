<?php
App::uses('Cousine', 'Model');

/**
 * Cousine Test Case
 *
 */
class CousineTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.cousine',
		'app.restaurant',
		'app.category',
		'app.category_locale',
		'app.cousine_locale'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Cousine = ClassRegistry::init('Cousine');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Cousine);

		parent::tearDown();
	}

}
