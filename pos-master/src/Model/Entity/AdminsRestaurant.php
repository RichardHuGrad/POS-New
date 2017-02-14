<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdminsRestaurant Entity
 *
 * @property int $id
 * @property int $admin_id
 * @property int $restaurant_id
 *
 * @property \App\Model\Entity\Admin $admin
 * @property \App\Model\Entity\Restaurant $restaurant
 */
class AdminsRestaurant extends Entity
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
