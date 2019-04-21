<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class RightMenuWidget extends Widget
{
	public $item_list;
	public $template = '
	<li>{link}</li>';
	
	public function run()
	{
		echo $this->renderItems();
	}
	
	protected function renderItems()
	{
		$lines = [];
		
		if(!is_null($this->item_list) && !empty($this->item_list))
		{
			$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
			$lines[] = '<ul class="links">';
			
			foreach ($this->item_list as $i => $item) 
			{
				$lines[] = $this->renderItem($item, $url);
			}
			
			$lines[] = '</ul>';
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($item, $url = '')
    {
		$explodedUrl = explode("/", $item['url']);
		
		return strtr($this->template, [
			'{link}' => Html::a($item['label'], $item['url'], $options = ['class'=>($url == end($explodedUrl)) ? 'active' : ''])
		]);
    }
}
