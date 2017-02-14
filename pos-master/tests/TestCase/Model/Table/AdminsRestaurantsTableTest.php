<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdminsRestaurantsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdminsRestaurantsTable Test Case
 */
class AdminsRestaurantsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AdminsRestaurantsTable
     */
    public $AdminsRestaurants;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.admins_restaurants',
        'app.admins',
        'app.restaurants',
        'app.categories',
        'app.category_locales',
        'app.cousines',
        'app.cousine_locales',
        'app.orders'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AdminsRestaurants') ? [] : ['className' => 'App\Model\Table\AdminsRestaurantsTable'];
        $this->AdminsRestaurants = TableRegistry::get('AdminsRestaurants', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AdminsRestaurants);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
