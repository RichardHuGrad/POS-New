<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CousineLocalesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CousineLocalesTable Test Case
 */
class CousineLocalesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CousineLocalesTable
     */
    public $CousineLocales;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cousine_locales',
        'app.cousines',
        'app.restaurants',
        'app.categories',
        'app.category_locales',
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
        $config = TableRegistry::exists('CousineLocales') ? [] : ['className' => 'App\Model\Table\CousineLocalesTable'];
        $this->CousineLocales = TableRegistry::get('CousineLocales', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CousineLocales);

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
