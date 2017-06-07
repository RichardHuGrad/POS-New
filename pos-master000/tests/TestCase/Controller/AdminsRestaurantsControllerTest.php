<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AdminsRestaurantsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\AdminsRestaurantsController Test Case
 */
class AdminsRestaurantsControllerTest extends IntegrationTestCase
{

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
