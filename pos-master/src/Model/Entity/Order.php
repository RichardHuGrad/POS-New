<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property int $restaurant_id
 * @property int $restaurant_order
 * @property string $order_no
 * @property int $table_no
 * @property float $tax
 * @property float $tax_amount
 * @property float $subtotal
 * @property float $total
 * @property float $card_val
 * @property float $cash_val
 * @property float $tip
 * @property string $tip_paid_by
 * @property float $paid
 * @property float $change
 * @property string $promocode
 * @property string $message
 * @property string $reason
 * @property string $order_type
 * @property string $is_completed
 * @property string $paid_by
 * @property float $fix_discount
 * @property float $percent_discount
 * @property float $discount_value
 * @property float $after_discount
 * @property int $merge
 *
 * @property \App\Model\Entity\Restaurant $restaurant
 */
class Order extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
