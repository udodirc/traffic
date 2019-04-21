<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$this->registerCssFile(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_css'.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'widgets.css'));
?>
<div class="partners-levels">
	<div class="col-sm-6 col-md-6">
		<h4 class="section-subtitle"><?= Yii::t('form', 'Уровни'); ?></h4>
		<div class="panel b-primary bt-sm">
			<div class="panel-content">
				<div class="widget-list list-left-element list-sm">
					<?php if($levels): ?>
					<ul>
						<?php for($i=1; $i<=$levels; $i++): ?>
							<li>
								<?= Html::a('<div class="left-element"><i class="fa fa-tasks "></i></div>
                                <div class="text">
									<span class="title">'.$i.'&nbsp;'.Yii::t('form', 'Уровень').'</span>
								</div>', ['/partners/partners-level/'.$i.'/'.(($demo) ? 1 : 0)]); ?>
							</li>
						<?php endfor; ?>
					</ul>
					<?php endif; ?>    
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-6">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Yii::t('form', 'Информация по заработку'); ?></h4>
			</div>
			<div class="panel-content">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>  
			</div>
		</div>
	</div>
</div>
