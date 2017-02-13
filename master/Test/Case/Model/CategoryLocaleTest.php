<?php
App::uses('CategoryLocale', 'Model');

/**
 * CategoryLocale Test Case
 *
 */
class CategoryLocaleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.category_locale',
		'app.category',
		'app.restaurant',
		'app.cousine'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CategoryLocale = ClassRegistry::init('CategoryLocale');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CategoryLocale);

		parent::tearDown();
	}

}
