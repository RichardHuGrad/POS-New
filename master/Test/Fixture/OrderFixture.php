<?php
/**
 * OrderFixture
 *
 */
class OrderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'restaurant_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'restaurant_order_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'order_no' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'table_no' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'tax' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '10,2'),
		'tax_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'subtotal' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'total' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'card_val' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'cash_val' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'tip' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'paid' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'change' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'promocode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'message' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'reason' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'fix_discount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'percent_discount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'discount_value' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'after_discount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'merge_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'restaurant_id' => 1,
			'restaurant_order_id' => 1,
			'order_no' => 'Lorem ip',
			'table_no' => 1,
			'tax' => 1,
			'tax_amount' => 1,
			'subtotal' => 1,
			'total' => 1,
			'card_val' => 1,
			'cash_val' => 1,
			'tip' => 1,
			'paid' => 1,
			'change' => 1,
			'promocode' => 'Lorem ipsum dolor sit amet',
			'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'reason' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'fix_discount' => 1,
			'percent_discount' => 1,
			'discount_value' => 1,
			'after_discount' => 1,
			'merge_id' => 1
		),
	);

}
