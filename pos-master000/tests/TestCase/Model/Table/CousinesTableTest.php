<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CousinesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CousinesTable Test Case
 */
class CousinesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CousinesTable
     */
    public $Cousines;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cousines',
        'app.restaurants',
        'app.categories',
        'app.category_locales',
        'app.orders',
        'app.restaurant_orders',
        'app.merges',
        'app.cousine_locales'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Cousines') ? [] : ['className' => 'App\Model\Table\CousinesTable'];
        $this->Cousines = TableRegistry::get('Cousines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Cousines);

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
