<?php
namespace common\modules\tickets\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class TicketsListWidget extends Widget
{
	public $item_list;
	public $template = '';
	public $templateName = '';
	
	public function run()
	{
		echo $this->renderItems();
	}
	
	protected function renderItems()
	{
		$lines = [];
		
		if(!is_null($this->item_list) && !empty($this->item_list))
		{
			$lines[] = '';
			
			foreach ($this->item_list as $i => $item) 
			{
				$lines[] = $this->renderItem($item);
			}
			
			$lines[] = '';
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($item, $separator = true)
    {
		$this->template = self::getTemplate($this->templateName);
		
		return ($this->template != '') ? strtr($this->template, [
			'{subject}' => $item->subject,
			'{date}' => date('m-d-Y', $item->created_at),
			'{url}' => \Yii::$app->request->BaseUrl.'/ticket/'.$item->id,
		]) : '';
    }
	
	protected static function getTemplate($templateName)
    {
		$result = '';
		
		switch($templateName)
		{
			case 'neon':
				$result = '
				<li>
					<a href="{url}">
						<div>
							<i class="fa fa-envelope fa-fw"></i>&nbsp;{subject}
							<span class="pull-right text-muted small">{date}</span>
					   </div>
				   </a>
				 </li>
				 <li class="divider"></li>';
			break;
			
			case 'hi-lite':
				$result = '
				<a class="dropdown-item preview-item">
					<div class="preview-thumbnail">
						<div class="preview-icon bg-success">
							<i class="mdi mdi-information mx-0"></i>
						</div>
					</div>
					<div class="preview-item-content">
						<h6 class="preview-subject font-weight-normal" style="text-transform: capitalize;">{subject}</h6>
						<p class="font-weight-light small-text mb-0 text-muted">
							{date}
						</p>
					</div>
				</a>';
			break;
		}
		
		return $result;
	}
}
