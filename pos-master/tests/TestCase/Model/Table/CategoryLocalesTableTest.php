<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoryLocalesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoryLocalesTable Test Case
 */
class CategoryLocalesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoryLocalesTable
     */
    public $CategoryLocales;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.category_locales',
        'app.categories',
        'app.restaurants',
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
        $config = TableRegistry::exists('CategoryLocales') ? [] : ['className' => 'App\Model\Table\CategoryLocalesTable'];
        $this->CategoryLocales = TableRegistry::get('CategoryLocales', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CategoryLocales);

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
