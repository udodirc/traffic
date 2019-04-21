<?php
namespace common\modules\faq\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class FaqWidget extends Widget
{
	public $section_number;
	public $item_list;
	public $template = '
	<div class="panel panel-default ">
		<div class="panel-heading" role="tab" id="heading_{number}">
			<a class="collapsed" role="button" href="#collapse_{section_number}_{number}" data-toggle="collapse" data-parent="#accordion{section_number}" aria-expanded="false" aria-controls="heading_{number}">
				<span class="panel-heading_icon"></span>
				{question} 
			</a>
		</div>
		<div class="answer" style="display: none;">
			<div class="panel-body">
				{answer}
			</div>
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
			$lines[] = '<div id="accordion'.$this->section_number.'" class="accordion panel-group collapse in" role="tablist" aria-multiselectable="true" aria-expanded="true" style="">';
			
			foreach ($this->item_list as $i => $item) 
			{
				$lines[] = $this->renderItem($this->section_number, $i, $item);
			}
			
			$lines[] = '</div>';
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($section_number, $number, $item)
    {
		return strtr($this->template, [
			'{question}' => $item['question'],
			'{answer}' => $item['answer'],
			'{number}' => ($number + 1),
			'{section_number}' => ($section_number),
		]);
    }
}
