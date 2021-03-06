<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "order_transaction".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $payment_type_id
 * @property string $start_date
 * @property string $end_date
 * @property integer $status
 * @property float $total_sum
 * @property string $params
 * @property string $result_data
 * @property Order $order
 * @property PaymentType $paymentType
 */
class OrderTransaction extends ActiveRecord
{
    const TRANSACTION_START = 1;
    const TRANSACTION_CHECKING = 2;
    const TRANSACTION_TIMEOUT = 3;
    const TRANSACTION_ROLLBACK = 4;
    const TRANSACTION_SUCCESS = 5;
    const TRANSACTION_ERROR = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'payment_type_id', 'status', 'total_sum'], 'required'],
            [['order_id', 'payment_type_id', 'status'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['total_sum'], 'number'],
            [['params', 'result_data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'order_id' => Yii::t('shop', 'Order ID'),
            'payment_type_id' => Yii::t('shop', 'Payment Type ID'),
            'start_date' => Yii::t('shop', 'Start Date'),
            'end_date' => Yii::t('shop', 'End Date'),
            'status' => Yii::t('shop', 'Status'),
            'total_sum' => Yii::t('shop', 'Total Sum'),
            'params' => Yii::t('shop', 'Params'),
            'result_data' => Yii::t('shop', 'Result Data'),
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getPaymentType()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
    }

    /**
     * @param integer $status
     * @return bool
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        return $this->save(true, ['status']);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->end_date = new Expression('NOW()');
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (isset($changedAttributes['status']) && $changedAttributes['status'] == 1
            && $this->status == self::TRANSACTION_SUCCESS
        ) {
            $this->order->order_status_id = 3;
            $this->order->save(true, ['order_status_id']);
        }
    }
}
