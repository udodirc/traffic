<?php
namespace common\modules\structure\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class PricesListWidget extends Widget
{
	public $item_list;
	public $type;
	public $template = '
	
	<div class="col-sm-6 col-xs-12">
		<div class="bg-success top_cards">
			<a href="{base_url}/partners/pay/{id}/{type}/{amount}" class="bg-success top_cards">
				<div class="row icon_margin_left">
					<div class="col-lg-5 icon_padd_left">
						<div class="float-xs-left">
							<span class="fa-stack fa-sm">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-eye fa-stack-1x fa-inverse text-success visit_icon"></i>
							</span>
                         </div>
                     </div>
                     <div class="col-lg-7 icon_padd_right">
						<div class="float-xs-right cards_content">
							<span class="number_val" id="visitors_count">{amount}$</span>
							<i class="fa fa-long-arrow-up fa-2x"></i>
                            <br/>
                            <span class="card_description">{title}</span>
                        </div>
                    </div>
				</div>
			</a>
		</div>
	</div>';
	
	public function run()
	{
		echo $this->renderItems();
	}
	
	protected function renderItems()
	{
		$lines = [];
		
		if(!is_null($this->item_list) && !empty($this->item_list))
		{
			foreach ($this->item_list as $i => $item) 
			{
				$lines[] = $this->renderItem($i, $item);
			}
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($amount, $item)
    {
		return strtr($this->template, [
			'{base_url}' => \Yii::$app->request->BaseUrl,
			'{id}' => (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0,
			'{type}' => $this->type,
			'{amount}' => $amount,
			'{title}' => Yii::t('form', 'Оплатить'),
		]);
    }
}
