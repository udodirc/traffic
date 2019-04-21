<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace common\components\perfectmoney;

use yii\bootstrap\Widget;
use yii\web\View;

class RedirectForm extends Widget
{
    public $message = 'Now you will be redirected to the payment system.';

    public $api;
    public $invoiceId;
    public $amount;
    public $description = '';

    public function init()
    {
        parent::init();
        assert(isset($this->api));
        assert(isset($this->invoiceId));
        assert(isset($this->amount));
    }

    public function run()
    {
        $this->view->registerJs("$('#perfect-money-checkout-form').submit();", View::POS_READY);
        return $this->render('redirect', [
            'message' => $this->message,
            'api' => $this->api,
            'invoiceId' => $this->invoiceId,
            'amount' => number_format($this->amount, 2, '.', ''),
            'description' => $this->description,
        ]);
    }
}
