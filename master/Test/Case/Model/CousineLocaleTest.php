<?php
App::uses('CousineLocale', 'Model');

/**
 * CousineLocale Test Case
 *
 */
class CousineLocaleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.cousine_locale',
		'app.cousine'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CousineLocale = ClassRegistry::init('CousineLocale');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CousineLocale);

		parent::tearDown();
	}

}
