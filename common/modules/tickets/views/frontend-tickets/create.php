<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\tickets\models\Tickets */

?>
<div class="tickets-create">
    <?= $this->render('_form'.$theme, [
        'model' => $model,
    ]) ?>
</div>
