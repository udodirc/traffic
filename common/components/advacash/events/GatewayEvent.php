<?php
/**
 * @author Valentin Konusov <rlng-krsk@yandex.ru>
 */

namespace common\components\advacash\events;

use yii\base\Event;
use yii\db\ActiveRecord;

class GatewayEvent extends Event
{
    const EVENT_PAYMENT_REQUEST = 'eventPaymentRequest';
    const EVENT_PAYMENT_SUCCESS = 'eventPaymentSuccess';

    /** @var ActiveRecord|null */
    public $invoice;

    /** @var array */
    public $gatewayData = [];
}
