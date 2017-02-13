<?php
App::uses('Restaurant', 'Model');

/**
 * Restaurant Test Case
 *
 */
class RestaurantTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.restaurant',
		'app.category',
		'app.category_locale',
		'app.cousine',
		'app.cousine_locale',
		'app.order',
		'app.restaurant_order',
		'app.merge'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Restaurant = ClassRegistry::init('Restaurant');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Restaurant);

		parent::tearDown();
	}

}
