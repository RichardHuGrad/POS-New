<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Restaurants
 *
 * @method \App\Model\Entity\Order get($primaryKey, $options = [])
 * @method \App\Model\Entity\Order newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Order[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Order[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order findOrCreate($search, callable $callback = null, $options = [])
 */
class OrdersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('orders');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Restaurants', [
            'foreignKey' => 'restaurant_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('restaurant_order')
            ->requirePresence('restaurant_order', 'create')
            ->notEmpty('restaurant_order');

        $validator
            ->requirePresence('order_no', 'create')
            ->notEmpty('order_no');

        $validator
            ->integer('table_no')
            ->allowEmpty('table_no');

        $validator
            ->decimal('tax')
            ->allowEmpty('tax');

        $validator
            ->decimal('tax_amount')
            ->allowEmpty('tax_amount');

        $validator
            ->decimal('subtotal')
            ->allowEmpty('subtotal');

        $validator
            ->decimal('total')
            ->allowEmpty('total');

        $validator
            ->decimal('card_val')
            ->allowEmpty('card_val');

        $validator
            ->decimal('cash_val')
            ->allowEmpty('cash_val');

        $validator
            ->decimal('tip')
            ->allowEmpty('tip');

        $validator
            ->allowEmpty('tip_paid_by');

        $validator
            ->decimal('paid')
            ->allowEmpty('paid');

        $validator
            ->decimal('change')
            ->allowEmpty('change');

        $validator
            ->allowEmpty('promocode');

        $validator
            ->allowEmpty('message');

        $validator
            ->allowEmpty('reason');

        $validator
            ->allowEmpty('order_type');

        $validator
            ->allowEmpty('is_completed');

        $validator
            ->allowEmpty('paid_by');

        $validator
            ->decimal('fix_discount')
            ->allowEmpty('fix_discount');

        $validator
            ->decimal('percent_discount')
            ->allowEmpty('percent_discount');

        $validator
            ->decimal('discount_value')
            ->allowEmpty('discount_value');

        $validator
            ->decimal('after_discount')
            ->allowEmpty('after_discount');

        $validator
            ->integer('merge')
            ->requirePresence('merge', 'create')
            ->notEmpty('merge');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['restaurant_id'], 'Restaurants'));

        return $rules;
    }
}
