<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Menu;

class BottomMenuWidget extends Widget
{
	public $category_id;
	public $sections_number;
	public $template = '
	<div class="moduletable footer_{number}  col-sm-3">
		<div class="module_container">
			<div class="mod-article-single mod-article-single__footer_{number}" id="module_{number}">
				<div class="item__module visible-first" id="item_{number}">
					<!-- Intro Text -->
					<div class="item_introtext">
						<h6 class="heading-style-6 visible-first">
							<span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first">{menu}</span>
						</h6>
						<div>
							{items}
						</div>	
					</div>	
				</div>
			</div>
		</div>
	</div>';
	public $submenuTemplate = '
		<li><a href="{url}">{menu}</a></li>';
	
	public function run()
	{
		echo $this->renderItems();
	}
	
	protected function renderItems()
	{
		$lines = [];
		$menuList = Menu::find()->select(['id', 'parent_id', 'name', 'partner_status',  'url'])->where('parent_id = 0 AND category_id = :category_id AND status != :status', ['category_id'=>$this->category_id, 'status'=>0])->asArray()->all();
		$submenuList = Menu::find()->select(['parent_id', 'name', 'url'])->where('parent_id > 0 AND category_id = :category_id AND status != :status', [':category_id'=>$this->category_id, ':status'=>0])->asArray()->all();
		$submenuList = ArrayHelper::map($submenuList, 'name', 'url', 'parent_id');
		
		if(!is_null($menuList) && !empty($menuList))
		{
			$counter = round((count($menuList) / $this->sections_number), 0, PHP_ROUND_HALF_UP);
			$nextLevel = 1;
			$count = $counter;
			
			for($i=1; $i<=$this->sections_number; $i++)
			{
				for($j=$nextLevel; $j<=$count; $j++)
				{	
					if(isset($menuList[($j-1)]['name']))
					{	
						$items = '';
						
						if(isset($submenuList[$menuList[($j-1)]['id']]))
						{
							$submenuLines = [];
							$submenuData = $submenuList[$menuList[($j-1)]['id']];
							$submenuLines[] = '<ul class="list1">';
							
							foreach($submenuData as $menu=>$url)
							{
								$submenuLines[] = $this->renderSubmenuItems($menu, $url);
							}
							
							$submenuLines[] = '</ul>';
							$items = implode("\n", $submenuLines);
						}
						
						$lines[] = $this->renderItem($menuList[($j-1)], $i, $items);
					}
				}
				
				$nextLevel+= $counter;
				$count+= $counter;
			}
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($item, $number, $items)
    {
		return strtr($this->template, [
			'{number}' => $number,
			'{url}' => $item['url'],
			'{menu}' => $item['name'],
			'{items}' => $items,
		]);
    }
    
    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderSubmenuItems($menu, $url)
    {
		return strtr($this->submenuTemplate, [
			'{url}' => $url,
			'{menu}' => $menu,
		]);
	}
}
