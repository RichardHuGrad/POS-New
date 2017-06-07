<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cousine Entity
 *
 * @property int $id
 * @property int $restaurant_id
 * @property float $price
 * @property int $category_id
 * @property int $comb_num
 * @property string $status
 * @property string $is_tax
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property string $is_synced
 *
 * @property \App\Model\Entity\Restaurant $restaurant
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\CousineLocale[] $cousine_locales
 */
class Cousine extends Entity
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
