<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CousineLocale Entity
 *
 * @property int $id
 * @property int $cousine_id
 * @property string $name
 * @property string $lang_code
 * @property int $created
 * @property int $modified
 *
 * @property \App\Model\Entity\Cousine $cousine
 */
class CousineLocale extends Entity
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
