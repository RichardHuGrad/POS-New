<?php
/**
 * CousineFixture
 *
 */
class CousineFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'restaurant_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comb_num' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'utf8_general_ci', 'comment' => 'A=Active, I=Inactive', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'price' => 1,
			'category_id' => 1,
			'comb_num' => 1,
			'status' => 'Lorem ipsum dolor sit ame',
			'created' => '2017-02-13 16:56:19',
			'modified' => '2017-02-13 16:56:19'
		),
	);

}
