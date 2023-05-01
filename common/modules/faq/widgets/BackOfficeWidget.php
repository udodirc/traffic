<?php
namespace common\modules\faq\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\HtmlPurifier;

class BackOfficeWidget extends Widget
{
	public $item_list;
	public $theme;
	public $template = '';
	
	public function run()
	{
		echo $this->renderItems();
	}
	
	protected function renderItems()
	{
		$lines[] = '';
		
		if(!is_null($this->item_list) && !empty($this->item_list))
		{
			$this->template = self::getTemplate();
			
			foreach ($this->item_list as $i => $item) 
			{
				$lines[] = $this->renderItem(($i + 1), $item);
			}
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($number, $item)
    {
		return strtr($this->template, [
			'{title}' => Yii::t('form', 'Вопрос'),
			'{number}' => $number,
			'{question}' => HtmlPurifier::process($item['question']),
			'{answer}' => HtmlPurifier::process($item['answer']),
		]);
    }
    
    protected function getTemplate()
    {
		$result = '';
		
		switch($this->theme)
		{
			case '_neon':
			
			$result = '<div class="panel panel-accordion">
				<div class="panel-header bg-scale-0">
					<a class="panel-title" data-toggle="collapse" data-parent="#accordion_faq" href="#q{number}">
						<span class="color-primary text-bold">{title}:</span>{question}
					</a>
				</div>
				<div id="q{number}" class="panel-collapse collapse">
					<div class="panel-content">
						{answer}
				   </div>
				</div>
			</div>';
			
			case '_hilite':
			
			$result = 
			'<div class="card">
				<div class="card-header" id="headingOne">
					<h5 class="mb-0">
						<a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							{question}
						</a>
					</h5>
				</div>
				<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion-1">
					<div class="card-body">
						<p class="mb-0">{answer}</p>
					</div>
				</div>
			</div>';
		}
		
		return $result;
	}
}
