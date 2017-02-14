<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RestaurantsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RestaurantsTable Test Case
 */
class RestaurantsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RestaurantsTable
     */
    public $Restaurants;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.restaurants',
        'app.categories',
        'app.category_locales',
        'app.cousines',
        'app.cousine_locales',
        'app.orders',
        'app.restaurant_orders',
        'app.merges'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Restaurants') ? [] : ['className' => 'App\Model\Table\RestaurantsTable'];
        $this->Restaurants = TableRegistry::get('Restaurants', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Restaurants);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
