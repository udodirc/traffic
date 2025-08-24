<?php
namespace common\modules\structure\models;

use Yii;;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\DbBase;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\Payments;
use common\modules\backoffice\models\DemoPayments;
use common\modules\structure\models\DemoMatrix1;
use common\modules\structure\models\MatricesSettings;
use common\modules\structure\models\PaymentsFaul;
use common\modules\structure\models\Payment;
use common\modules\structure\models\AutoPayOffLogs;
use common\components\advacash\models\Advacash;
use common\models\Service;

/**
 * Matrix model
 */
class Matrix extends Model
{
	public $parent_matrix_id;
	public $main_parent_matrix_id;
	
	public static function getLinearMatrixDataByMatrixID($number, $matrixID,  $demo)
    {
		$linearCount = (isset(Yii::$app->params['matrices_linear_count'])) ? Yii::$app->params['matrices_linear_count'] : 0;
		
		if($linearCount > 0 && $number > 0 && $matrixID > 0)
		{
			$table = '';
			$table.= ($demo) ? 'demo_' : '';
			$table.= 'matrix_'.$number;
			
			return Partners::find()
			->select('`'.$table.'`.`partner_id` AS `id`, `'.$table.'`.`close_date`, `'.$table.'`.`open_date`, `partners`.`login`, `partners`.`status`')
			->from('`'.$table.'`, `'.$table.'` `main_matrix_'.$number.'`, `partners`')
			->where('`main_matrix_'.$number.'`.`id`=:id AND `'.$table.'`.`level` >= `main_matrix_'.$number.'`.`level` AND `'.$table.'`.`level` <= (`main_matrix_'.$number.'`.`level` + '.$linearCount.') AND `partners`.`id` = `'.$table.'`.`partner_id`', [':id' => $matrixID])
			->orderBy('`'.$table.'`.`level`, `'.$table.'`.`open_date`')
			->asArray()
			->all();
		}
	}
	
	public static function getBinaryMatrixDataByMatrixID($structureNumber, $matrixNumber, $leftKey, $rightKey, $level, $levelDepth, $demo, $accountType)
    {
		$table = '';
		$table.= ($demo) ? 'demo_' : '';
		
		if($leftKey > 0 && $rightKey > 0 && $level > 0)
		{
			switch($accountType)
			{
				case 1:
				$amount = '`'.$table.'matrix_payments`.`amount`';
				break;
										
				case 2:
				$amount = 'SUM(`'.$table.'matrix_payments`.`amount`) AS `amount`';
				break;
				
				default:
				$amount = '`'.$table.'matrix_payments`.`amount`';
				break;
			}
			
			return Partners::find()
			->select('`'.$table.'balls`.`balls`, '.$amount.', `'.$table.'matrix_payments`.`type`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`matrix_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`partner_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`clone`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`close_date`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`open_date`, `partners`.`login`, `partners`.`status`, TRIM(TRAILING "." FROM TRIM(TRAILING "0" FROM `gold_token`.`amount`)) AS `gold_token`')
			->from('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`')
			->leftJoin('`partners`', '`partners`.`id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`partner_id`')
			->leftJoin('`'.$table.'matrix_payments`', '`'.$table.'matrix_payments`.`matrix_id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id` AND `'.$table.'matrix_payments`.`type` > 1 AND `'.$table.'matrix_payments`.`matrix_number` = '.$matrixNumber.' AND `'.$table.'matrix_payments`.`structure_number` = '.$structureNumber)
			->leftJoin('`'.$table.'balls`', '`'.$table.'balls`.`matrix_id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id` AND `'.$table.'balls`.`type` > 1 AND `'.$table.'balls`.`matrix_number` = '.$matrixNumber.' AND `'.$table.'balls`.`structure_number` = '.$structureNumber)
			->leftJoin('`gold_token`', '`gold_token`.`matrix_id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id` AND `gold_token`.`matrix` = '.$matrixNumber.' AND `gold_token`.`amount` > 0 AND `gold_token`.`structure_number` = '.$structureNumber)
			->where('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key` >= :leftKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`right_key` <= :rightKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level` BETWEEN :level AND :level + :levelDepth', [':leftKey' => $leftKey, ':rightKey' => $rightKey, ':level' => $level, ':levelDepth' => $levelDepth])
			->groupBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`')
			->orderBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`open_date`')
			->asArray()
			->all();
		}
	}
	
	public static function getBinaryMatrixDataByMatrixIDInListView($structureNumber, $matrixNumber, $leftKey, $rightKey, $level, $levelDepth, $demo, $accountType)
    {
		$table = '';
		$table.= ($demo) ? 'demo_' : '';
		
		if($leftKey > 0 && $rightKey > 0 && $level > 0)
		{
			return Partners::find()
			->select('SUM(`'.$table.'matrix_payments`.`amount`) AS `amount`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`')
			->from('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`')
			->leftJoin('`'.$table.'matrix_payments`', '`'.$table.'matrix_payments`.`matrix_id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id` AND `'.$table.'matrix_payments`.`type` > 1 AND `'.$table.'matrix_payments`.`matrix_number` = '.$matrixNumber)
			->where('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key` >= :leftKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`right_key` <= :rightKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level` BETWEEN :level AND :level + :levelDepth', [':leftKey' => $leftKey, ':rightKey' => $rightKey, ':level' => $level, ':levelDepth' => $levelDepth])
			->groupBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`')
			->orderBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`open_date`')
			->asArray()
			->all();
		}
	}
	
	public static function getMatrixPaymentDataBySponsorID($structureNumber, $matrixNumber, $id, $levelDepth, $demo = false)
    {
		$result = [];
		$data = Matrix::getPartnerFromMatrix($structureNumber, $matrixNumber, $id, $demo);
		
		if(!empty($data))
        {
			$table = '';
			$table.= ($demo) ? 'demo_' : '';
			
			if($data['left_key'] > 0 && $data['right_key'] > 0 && $data['level'] > 0)
			{	
				$result = Partners::find()
				->select('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`matrix_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`partner_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `partners`.`login`, `partners`.`advcash_wallet`')
				->from('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`')
				->leftJoin('`partners`', '`partners`.`id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`partner_id`')
				->where('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key` <= :leftKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`right_key` >= :rightKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level` BETWEEN :level - :levelDepth AND :level AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id` != :id', [':leftKey' => $data['left_key'], ':rightKey' => $data['right_key'], ':level' => $data['level'], ':levelDepth' => $levelDepth, ':id' => $id])
				->groupBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`')
				->orderBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`open_date`')
				->asArray()
				->all();
				$result = ArrayHelper::index($result, 'level');
			}
		}
		
		return $result;
	}
	
	public static function getMatrixDataByLevel($structureNumber, $matrixNumber, $id, $level, $demo = false)
    {
		$result = [];
		$data = self::getPartnerFromMatrix($structureNumber, $matrixNumber, $id, $demo);
		
		if(!empty($data))
        {
			$table = '';
			$table.= ($demo) ? 'demo_' : '';
			
			if($data['left_key'] > 0 && $data['right_key'] > 0)
			{	
				$result = Partners::find()
				->select('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`matrix_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`partner_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`parent_id`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`open_date`, `partners`.`login`, `parent_matrix_partner`.`login` AS `parent_matrix_login`, `sponsor_partner`.`login` AS `sponsor_login`, `sponsor_partner`.`id` AS `sponsor_id`')
				->from('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`')
				->leftJoin('`partners`', '`partners`.`id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`partner_id`')
				->leftJoin('`partners` `sponsor_partner`', '`sponsor_partner`.`id` = `partners`.`sponsor_id`')
				->leftJoin('`partners` `parent_matrix_partner`', '`parent_matrix_partner`.`id` = `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`parent_id`')
				->where('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key` >= :leftKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`right_key` <= :rightKey AND `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level` = :level', [':leftKey' => $data['left_key'], ':rightKey' => $data['right_key'], ':level' => $level])
				->orderBy('`'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`level`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`left_key`, `'.$table.'matrix_'.$structureNumber.'_'.$matrixNumber.'`.`open_date`')
				->asArray()
				->all();
			}
		}
		
		return $result;
	}
	
	public static function getMatrixDataByID($structureNumber, $matrixNumber, $id, $demo = false, $type = 2)
    {
		$result = [];
		
		if($structureNumber > 0 && $matrixNumber > 0)
		{
			$matrixData = self::getPartnerFromMatrix($structureNumber, $matrixNumber, $id, $demo);
			$matricesSettings = Matrix::getMatricesSettings($structureNumber, $matrixNumber);
			
			if((!empty($matrixData)) && (!empty($matricesSettings)))
			{
				if($matrixData['left_key'] > 0 && $matrixData['right_key'] > 0)
				{	
					$data = self::getBinaryMatrixDataByMatrixID($structureNumber, $matrixNumber, $matrixData['left_key'], $matrixData['right_key'], $matrixData['level'], $matricesSettings['levels'], $demo, $matricesSettings['account_type']);
					$result = self::getMatrixDataStructure($data, $id, $matrixData, $type);
				}
			}
		}
		
		return $result;
	}
	
	public static function getMatrixDataStructure($data, $id, $matrixData, $type, $index = 0)
    {
		$result = [];
		
		if(!empty($data))
		{	
			$result2 = [];
			$result3 = [];
					
			foreach($data as $index=>$dataItem)
			{	
				if($type == 1)
				{	
					$result[$id]['matrix_structure'][] = $dataItem;
											
					if(!isset($result[$id]['matrix_info']))
					{
						$result[$id]['matrix_info'][0] = [0, $dataItem['open_date'], $dataItem['close_date']];
					}
				}
				else
				{
					if(!isset($result[$id]['matrix_info']))
					{	
											
						$amount = ($dataItem['type'] > 0) ? $dataItem['amount'] : 0;
						$balls = ($dataItem['balls'] > 0) ? $dataItem['balls'] : 0;
						$result[$id]['matrix_info'][0] = [$amount, $balls, $dataItem['open_date'], $dataItem['close_date']];
					}
										
					$result[$id]['child_structure'][$dataItem['id']]['data'] = $dataItem;
					$result[$id]['child_structure'][$dataItem['id']]['child'] = [];
										
					if(isset($result[$id]['child_structure'][$dataItem['matrix_id']]))
					{
						$result[$id]['child_structure'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
						$result[$id]['child_structure'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
						$result2[$dataItem['id']] = $dataItem['matrix_id'];
											
						if(isset($result2[$dataItem['matrix_id']]))
						{
							$result3[$dataItem['matrix_id']] = $dataItem['id'];
						}
					}
										
					if(isset($result2[$dataItem['matrix_id']]))
					{
						if($matrixData['clone'] > 0)
						{
							if($index > 0)
							{	
								$result[$id]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
								$result[$id]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
							}
													
						}
						else
						{	
							if(isset($result[$id]['child_structure'][$dataItem['matrix_id']]['data']))
							{								
								$result[$id]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['data'] = $result[$id]['child_structure'][$dataItem['matrix_id']]['data'];
								$result[$id]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
								$result[$id]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
							}
						}
					}
										
					if(isset($result3[$dataItem['matrix_id']]))
					{
						if(isset($result2[$dataItem['matrix_id']]))
						{
							if(isset($result2[$result2[$dataItem['matrix_id']]]))
							{
								$matrixID = $result2[$result2[$dataItem['matrix_id']]];
														
								if(isset($result[$matrixID]))
								{
									$result[$id]['child_structure'][$matrixID]['child'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
									$result[$id]['child_structure'][$matrixID]['child'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
								}
							}
						}
					}
				}
			} 
		}
		
		return $result;
	}
	
	public static function getPartnerFromMatrix($structureNumber, $matrixNumber, $matrixID, $demo)
    {
		$result = [];
		$demo = ($demo) ? 'demo_' : '';
		$sql = "SELECT * 
		FROM `".$demo."matrix_".$structureNumber."_".$matrixNumber."` 
		WHERE `id` = '".$matrixID."'";
		
		$connection = \Yii::$app->db;
		$result = $connection->createCommand($sql)->queryOne();
		
		return $result;
	}
	
	public static function getLastMatrixIDByPartnerID($structureNumber, $matrixNumber, $partnerID, $demo)
    {
		$result = 0;
		$demo = ($demo) ? 'demo_' : '';
		$sql = "SELECT `id` 
		FROM `".$demo."matrix_".$structureNumber."_".$matrixNumber."` 
		WHERE `partner_id` = '".$partnerID."'
		ORDER BY `id` DESC
		LIMIT 0,1";
		
		$connection = \Yii::$app->db;
		$matrixID = $connection->createCommand($sql)->queryOne();
		
		$result = (!empty($matrixID) && isset($matrixID['id'])) ? $matrixID['id'] : 0;
		
		return $result;
	}
	
	public static function getAllMatricesBySponsorID($number, $sponsorID, $demo = false)
    {
		$result = [];
		$data = null;
		
		for($i=1; $i <= $number; $i++)
		{	
			$data = self::getPlatformDataByNumber($i, $sponsorID, $demo);
			
			if(!empty($data))
			{
				$result[$i] = $data;
			}
		}
		
		return $result;
	}
	
	public static function getPlatformDataByNumberInListView($structureNumber, $number, $sponsorID, $type, $levelDepth, $demo, $accountType = 1)
    {
		$result = [];
		$partnersList = self::getPartnersFromMatrix($structureNumber, $number, $sponsorID, $demo);
			
		if(!empty($partnersList))
		{
			foreach($partnersList as $j => $item)
			{	
				if($type == 1)
				{
					$data = self::getLinearMatrixDataByMatrixID($number, $item['id'], $demo);
				}
				else
				{	
					$data = self::getBinaryMatrixDataByMatrixIDInListView($structureNumber, $number, $item['left_key'], $item['right_key'], $item['level'], $levelDepth, $demo, $accountType);
				}
						
				if(!is_null($data) || !empty($data))
				{	
					if(!is_null($data) || !empty($data))
					{	
						foreach($data as $index=>$dataItem)
						{
							if($type == 1)
							{	
								$result[$item['id']]['matrix_structure'][] = $dataItem;
											
								if(!isset($result[$item['id']]['matrix_info']))
								{
									$result[$item['id']]['matrix_info'][0] = [0, $dataItem['open_date'], $dataItem['close_date']];
								}
							}
							else
							{
								$result[$item['id']]['levels'][$dataItem['level']][$dataItem['id']] = $dataItem['amount'];
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function getPlatformDataByNumber($structureNumber, $number, $sponsorID, $type, $levelDepth, $demo, $accountType = 1)
    {
		$result = [];
		$result2 = [];
		$result3 = [];
		$partnersList = self::getPartnersFromMatrix($structureNumber, $number, $sponsorID, $demo);
				
		if(!empty($partnersList))
		{	
			foreach($partnersList as $j => $item)
			{	
				if($type == 1)
				{
					$data = self::getLinearMatrixDataByMatrixID($number, $item['id'], $demo);
				}
				else
				{	
					$data = self::getBinaryMatrixDataByMatrixID($structureNumber, $number, $item['left_key'], $item['right_key'], $item['level'], $levelDepth, $demo, $accountType);
				}
						
				if(!is_null($data) || !empty($data))
				{	
					foreach($data as $index=>$dataItem)
					{	
						if($number > 0)
						{	
							if($type == 1)
							{	
								$result[$item['id']]['matrix_structure'][] = $dataItem;
											
								if(!isset($result[$item['id']]['matrix_info']))
								{
									$result[$item['id']]['matrix_info'][0] = [0, $dataItem['open_date'], $dataItem['close_date']];
								}
							}
							else
							{
								if(!isset($result[$item['id']]['matrix_info']))
								{	
											
									$amount = ($dataItem['type'] > 0) ? $dataItem['amount'] : 0;
									$balls = ($dataItem['balls'] > 0) ? $dataItem['balls'] : 0;
									$result[$item['id']]['matrix_info'][0] = [$amount, $balls, $dataItem['open_date'], $dataItem['close_date']];
								}
										
								$result[$item['id']]['child_structure'][$dataItem['id']]['data'] = $dataItem;
								$result[$item['id']]['child_structure'][$dataItem['id']]['child'] = [];
										
								if(isset($result[$item['id']]['child_structure'][$dataItem['matrix_id']]))
								{
									$result[$item['id']]['child_structure'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
									$result[$item['id']]['child_structure'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
									$result2[$dataItem['id']] = $dataItem['matrix_id'];
											
									if(isset($result2[$dataItem['matrix_id']]))
									{
										$result3[$dataItem['matrix_id']] = $dataItem['id'];
									}
								}
										
								if(isset($result2[$dataItem['matrix_id']]))
								{
									if($item['clone'] > 0)
									{
										if($index > 0)
										{	
											$result[$item['id']]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
											$result[$item['id']]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
										}
													
									}
									else
									{	
										if(isset($result[$item['id']]['child_structure'][$dataItem['matrix_id']]['data']))
										{								
											$result[$item['id']]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['data'] = $result[$item['id']]['child_structure'][$dataItem['matrix_id']]['data'];
											$result[$item['id']]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
											$result[$item['id']]['child_structure'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
										}
									}
								}
										
								if(isset($result3[$dataItem['matrix_id']]))
								{
									if(isset($result2[$dataItem['matrix_id']]))
									{
										if(isset($result2[$result2[$dataItem['matrix_id']]]))
										{
											$matrixID = $result2[$result2[$dataItem['matrix_id']]];
														
											if(isset($result[$matrixID]))
											{
												$result[$item['id']]['child_structure'][$matrixID]['child'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['data'] = $dataItem;
												$result[$item['id']]['child_structure'][$matrixID]['child'][$result2[$dataItem['matrix_id']]]['child'][$dataItem['matrix_id']]['child'][$dataItem['id']]['child'] = [];
											}
										}
									}
								}
							}
						} 
					}
				}
			}
		}
		
		return $result;
	}
	
	public function getSponsorMatrix($matrixID, $structure, $number, $demo)
    {
		$demo = ($demo) ? 'demo_' : '';
		
		$sql = "SELECT `sponsor_matrix`.`partner_id`
		FROM `".$demo."matrix_".$structure."_".$number."` AS `sponsor_matrix`
		LEFT JOIN `".$demo."matrix_".$structure."_".$number."` AS `partner_matrix` ON `sponsor_matrix`.`id` = `partner_matrix`.`matrix_id`
		WHERE `partner_matrix`.`id` = '".$matrixID."';";
		
		$connection = \Yii::$app->db;
		$result = $connection->createCommand($sql)->queryOne();
		$result = (isset($result['partner_id']) && $result['partner_id'] > 0) ? $result['partner_id'] : 0;
		
		return $result;
	}
	
	public function getLevelPaymentsByLevel($id, $level, $demo = false, $credit = false)
    {
		$result = null;
		$table = '';
		$table.= ($credit) ? 'credit_' : '';
		$table.= ($demo) ? 'demo_' : '';
		$table.= 'levels_payment';
		
		if($id > 0 && $level > 0)
		{
			$result = Partners::find()
			->select('`partners`.`login`, `partners`.`iso`, `'.$table.'`.`level`, `partners`.`created_at`,
			`'.$table.'`.`amount` AS `percent_amount`, `'.$table.'`.`created_at` AS `activation_date`')
			->from('`partners`')
			->leftJoin('`'.$table.'`', '`'.$table.'`.`refferal_id` = `partners`.`id`')
			->where('`'.$table.'`.`partner_id` = :id AND `'.$table.'`.`level` = :level', [':id' => $id, ':level' => $level])
			->orderBy('`partners`.`created_at`');
		}
		
		return $result;
	}
	
	public function getLevelDataByNumber($model, $level, $demo = false)
    {
		$result = null;
		$operand = ($demo) ? '=' : '>';
		$paymentTable = ($demo) ? 'demo_payments' : 'payments';
		$matrix = 0;
		
		if(((isset($model->left_key)) && $model->left_key > 0) && ((isset($model->right_key)) && $model->right_key > 0) && ((isset($model->level)) && $model->level > 0))
		{
			$result = Partners::find()
			->select('`partners_1`.`login` AS `sponsor_name`, `partners_2`.`login`, `partners_2`.`iso`, `partners_2`.`created_at`, `'.$paymentTable.'`.`amount` AS `percent_amount`, `'.$paymentTable.'`.`created_at` AS `activation_date`')
			->from('`partners` `partners_1`')
			->rightJoin('`partners` `partners_2`', '`partners_1`.`id` = `partners_2`.`sponsor_id`')
			->leftJoin('`'.$paymentTable.'`', '`'.$paymentTable.'`.`refferal_id` = `partners_2`.`id` AND `'.$paymentTable.'`.`partner_id` = :id AND `'.$paymentTable.'`.`payment_type` = :payment_type')
			->where('`partners_2`.`left_key` >= :left_key AND `partners_2`.`right_key` <= :right_key AND `partners_2`.`level` = :level AND `partners_2`.`matrix` '.$operand.' :matrix', [':id' => $model->id, ':left_key' => $model->left_key, ':right_key' => $model->right_key, ':level' => ($model->level + $level), ':matrix' => $matrix, ':payment_type' => 2])
			->orderBy('`partners_2`.`created_at`');
		}
		
		return $result;
	}
	
	public static function getPartnersFromMatrix($structureNumber, $matrixNumber, $sposnsorID, $demo)
    {
		$result = [];
		$demo = ($demo) ? 'demo_' : '';
		$sql = "SELECT * FROM `".$demo."matrix_".$structureNumber."_".$matrixNumber."` WHERE `partner_id` = '".$sposnsorID."'";
		
		$connection = \Yii::$app->db;
		$result = $connection->createCommand($sql)->queryAll();
		
		return $result;
	}
	
	public static function getDataFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID, $demo = false)
    {
		$result = [];
		$demo = ($demo) ? 'demo_' : '';
		$sql = "SELECT * 
		FROM `".$demo."matrix_".$structureNumber."_".$matrixNumber."` 
		WHERE `partner_id` = '".$partnerID."'
		ORDER BY `open_date` DESC, `id` DESC
		LIMIT 1";
		
		$connection = \Yii::$app->db;
		$result = $connection->createCommand($sql)->queryOne();
		
		return $result;
	}
	
	public static function getCountPlacesFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID, $demo = false)
    {
		$result = 0;
		
		if($partnerID > 0 && $structureNumber > 0 && $matrixNumber > 0)
		{
			$demo = ($demo) ? 'demo_' : '';
			$sql = "SELECT * 
			FROM `".$demo."matrix_".$structureNumber."_".$matrixNumber."` 
			WHERE `partner_id` = '".$partnerID."'";
			
			$connection = \Yii::$app->db;
			$result = $connection->createCommand($sql)->queryOne();
			$result = (!empty($result)) ? count($result) : 0;
		}
		
		return $result;
	}
	
	public static function getMaxMatrix($structureNumber = '1', $demo = false)
    {
		$result = 0;
		
		if($structureNumber > 0)
		{
			$demo = ($demo) ? 'demo_' : '';
			$sql = "SELECT MAX(`number`) AS `max_number`
			FROM `".$demo."matrices_settings_".$structureNumber."`";
			
			$connection = \Yii::$app->db;
			$result = $connection->createCommand($sql)->queryOne();
			$result = (isset($result['max_number'])) ? $result['max_number'] : 0;
		}
		
		return $result;
	}
	
	public function addPartnerInStructure($sponsorID, $partnerID, $structureNumber, $matrixNumber, $date = '', $demo = false, $status = 2, $reserve = false, $checkActivation = false, $pay = true, $paymentType = 0, $amount = 0, $transactID = '', $root = false, $change = false)
    {
		$result = false;
		
		if($checkActivation)
		{
			$partnerData = (Partners::find()->select(['status'])->where(['id'=>$partnerID]) !== null) ? Partners::find()->select(['status'])->where(['id'=>$partnerID])->one() : null;
		
			if($partnerData !== null)
			{
				if($partnerData->status > 1 && $matrixNumber == 1)
				{	
					return true;
				}
			}
		}
		
		$demo = ($demo) ? 1 : 0;
		$date = ($date !== '') ? $date : time();
		$demoActivation = (isset(\Yii::$app->params['demo_structure_activation']) && (\Yii::$app->params['demo_structure_activation']) && !($reserve)) ? 1 : 0;
		$matricesSettings = Matrix::getMatricesSettings($structureNumber, $matrixNumber);
		$reserve = ($reserve) ? 1 : 0;
		$root = ($root) ? 1 : 0;
		$change = ($change) ? 1 : 0;
		$procedureInData = [$sponsorID, $partnerID, $structureNumber, $matrixNumber, $date, $demo, $status, $reserve, $demoActivation, $root, $change, '@p1', '@p2'];
		
		if(!empty($procedureInData))
		{	
			$dbModel = new DbBase();
			$procedureOutData = ['@p1'=>'VAR_OUT_RESULT', '@p2'=>'VAR_OUT_RESULT2'];
			$procedureResult = $dbModel->callProcedure('partner_activation', $procedureInData, $procedureOutData);
			$result = $procedureResult['result'];
			
			if($result)
			{	
				if(isset($matricesSettings['account_type']) && (Service::isActionAllowed('is_payment_allowed')) && (isset($matricesSettings['auto_pay_off']) && $matricesSettings['auto_pay_off'] == '1'))
				{	
					if($matricesSettings['account_type'] == '2')
					{
						if((isset(Yii::$app->params['payments_types'])))
						{
							$insertInvoice = true;
							$matrixData = Matrix::getDataFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID);
							$matrixID = (!empty($matrixData)) ? $matrixData['id'] : 0;
							
							if($matrixID > 0 && $paymentType > 0 && $amount > 0 && $transactID != '')
							{
								$paymentsInvoicesModel = new PaymentsInvoices();
							
								if(!$paymentsInvoicesModel->insertInvoice($matrixID, $structureNumber, $matrixNumber, $partnerID, $partnerID, $matrixID, $paymentType, 1, $amount, $transactID))
								{	
									$insertInvoice = false;
									$model = new PaymentsFaul();
								
									$model->partner_id = $partnerID;
									$model->matrix_number = $matrixNumber;
									$model->matrix_id = 0;
									$model->paid_matrix_partner_id = $partnerID;
									$model->paid_matrix_id = 0;
									$model->payment_type = $paymentType;
									$model->amount = $amount;
									$model->note = Yii::t('messages', 'Ошибка платежа!');
									$model->created_at = time();
									$model->save(false);		
								}
							}
							
							if(($pay) && ($insertInvoice))
							{	
								$model = new Payment();
								$result = $model->activatePaymentByAccounting($structureNumber, $matrixNumber, $partnerID, $matricesSettings);
							}
						}
					}
					elseif(($matricesSettings['account_type'] == '1'))
					{	
						$result = AutoPayOffLogs::makeAutoPayment();
					}
				}
			}
		}
		
		return $result;
	}
	
	public function activateLevelMatrix($model, $demo = false, $amount = 0)
    {
		$result = false;
		
		if($amount > 0)
		{
			$activationAmount = $amount;
		}
		else
		{
			$activationAmount = (isset(Yii::$app->params['activation_amount'])) ? Yii::$app->params['activation_amount'] : 0;
		}
		
		$levelStructurePercentage = (isset(Yii::$app->params['level_structure_percentage'])) ? Yii::$app->params['level_structure_percentage'] : [];
		
		if($activationAmount > 0 && !empty($levelStructurePercentage))
		{
			$paymentModel = new Payments;
			$params = [];
			$levelsPartners = $paymentModel->calculateLevelsPercentage($model->id);
			$statusList = (isset(Yii::$app->params['status_amount'])) ? Yii::$app->params['status_amount'] : [];
			$status = (!empty($statusList) && isset($statusList[$activationAmount])) ? $statusList[$activationAmount] : 0;
			
			if(!empty($levelsPartners))
			{
				rsort($levelsPartners);
				
				foreach($levelsPartners as $key=>$partnerData)
				{
					$index = ($key + 1);
					
					if(isset($levelStructurePercentage[$index]))
					{
						$percent = (intval($activationAmount) / 100) * $levelStructurePercentage[$index];
						$params[] = [$partnerData['id'], $model->id, $index, 2, $percent, time()];
					}
				}
				
				if(!empty($params))
				{
					$paymentModel = ($demo) ? new DemoPayments : $paymentModel;
					$result = $this->activateLevelMatrixTransaction($model->id, $activationAmount, $params, $paymentModel, $demo, $status);
				}
			}
		}
		
		return $result;
	}
	
	public function activateLevelMatrixTransaction($id, $activationAmount, $params, $paymentModel, $demo, $status)
    {
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		
		try 
		{
			if(($partnersModel = Partners::findOne($id)) !== null) 
			{
				if($demo)
				{
					$partnersModel->demo_matrix = 1;
				}
				else
				{
					$partnersModel->matrix = 1;
					$partnersModel->status = ($status > 0) ? $status : 2;
				}
						
				if($partnersModel->save(false))
				{	
					$paymentModel->partner_id = $id;
					$paymentModel->refferal_id = $id;
					$paymentModel->level = 0;
					$paymentModel->payment_type = 1;
					$paymentModel->amount = $activationAmount;
				
					if($paymentModel->save(false))
					{	
						$updateResult = true;
						$partnersModel = null;
								
						foreach($params as $key=>$paramsData)
						{
							if(($partnersModel = Partners::findOne($paramsData[0])) !== null) 
							{
								if($demo)
								{
									$partnersModel->demo_total_amount+= $paramsData[4];
								}
								else
								{
									$partnersModel->total_amount+= $paramsData[4];
								}
							
								if(!$partnersModel->save(false))
								{
									$updateResult = false;
									break;
									$transaction->rollback();
								}
							}
						}
								
						if($updateResult)
						{
							$table = ($demo) ? 'demo_payments' : 'payments';
							$result = $connection->createCommand()->batchInsert($table, ['partner_id', 'refferal_id', 'level', 'payment_type', 'amount', 'created_at'], $params)->execute();
						
							if($result > 0)
							{	
								$transaction->commit();
				
								return true;
							}
						}
					}
				}
			}
			
			$transaction->rollback();
				
			return false;
		} 
		
		catch(Exception $e) 
		{
			$transaction->rollback();
		}
	}
	
	public function reservePlacesInStructure($sponsorID, $partnerID, $structureNumber, $matrixNumber, $status, $placesCount, $paymentType = '', $amount = 0, $transactID = '', $root = false, $change = false)
    {
		$result = false;
		$outResult = true;
		$matricesSettings = Matrix::getMatricesSettings($structureNumber, $matrixNumber);
		
		if($placesCount > 0 && !empty($matricesSettings))
		{	
			for($i=1; $i <= $placesCount; $i++)
			{	
				$matrixData = Matrix::getDataFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID);
				$reserve = ($matrixData != null && $matrixData['id'] > 0 && !$change) ? true : false;
				$sponsorID = ($reserve) ? $partnerID : $sponsorID;
				
				if(!$this->addPartnerInStructure($sponsorID, $partnerID, $structureNumber, $matrixNumber, $date = '', false, $status, $reserve, false, true, $paymentType, $amount, $transactID, $root, $change))
				{	
					$outResult = false;
					break;
				}
			}
		
			$result = $outResult;
		}
		
		return $result;
	}
	
	public function activateByPaymentSystem2($id, $count)
    {
		$result = false;
		
		if($id > 0 && $count > 0)
		{
			if(($model = Partners::findOne($id)) !== null) 
			{
				$activationResult = true;
				
				$structureType = (isset(\Yii::$app->params['structure_type'])) ? \Yii::$app->params['structure_type'] : 0;
				
				if($structureType > 0)
				{
					if($structureType > 1)
					{
						if($model->status ==   1)
						{
							$sposnorID = $model->sponsor_id;
						
							if($sposnorID > 0)
							{
								if(!$this->addPartnerInStructure($sposnorID, $id, 1))
								{
									$activationResult = false;
								}
								
								$count--;
							}
						}
							
						if($activationResult)
						{	
							$matrixCount = Matrix1::find()->where(['partner_id'=>$id])->count();
							$linearCount = (isset(Yii::$app->params['matrices_linear_count'])) ? Yii::$app->params['matrices_linear_count'] : 0;
							$status = (($matrixCount + $count) > $linearCount) ? 3 : 2;
							$result = $this->reservePlacesInStructure($id, 1, $status, $count);
						}
					}
					else
					{	
						$result = $this->activateLevelMatrix($model, false, $count);
					}
				}
			}
		}
		
		return $result;
	}
	
	public function reserveOnePlaceInStructure($model)
    {
		$result = false;
		$outResult = false;
		
		$matrixPayments = Yii::$app->params['matrix_payments'];
		$ballsList = Yii::$app->params['balls'];
		$matrixPaymentType = (isset($matrixPayments[$matrixNumber])) ? $matrixPayments[$matrixNumber]['type'] : 0;
		$matrixPayment = (isset($matrixPayments[$matrixNumber])) ? $matrixPayments[$matrixNumber]['amount'] : 0;
		$balls = (isset($ballsList[$matrixNumber])) ? $ballsList[$matrixNumber] : 0;
		$matrixCount = (isset(Yii::$app->params['matrices_linear_count'])) ? Yii::$app->params['matrices_linear_count'] : 0;
		$procedure = ($demo) ? 'demo_' : '';
		$procedureInData = [];
		
		if($matrixNumber > 1)
		{
			$procedure.='binar';
			$procedureInData = [$sponsorID, $partnerID, $matrixNumber, $date, $matrixPaymentType, $matrixPayment, $balls, '@p1', '@p2', '@p3', '@p4', '@p5'];
		}
		else
		{
			if($matrixCount > 0)
			{
				$procedure.='linear';
				$procedureInData = [$partnerID, $matrixNumber, $date, $matrixCount, $matrixPaymentType, $matrixPayment, $balls, '@p1', '@p2', '@p3', '@p4', '@p5'];
			}
		}
		
		$result = false;
		
		$matrixNumber = $model->matrix;
		$matrixPayments = Yii::$app->params['matrix_payments'];
		$ballsList = Yii::$app->params['balls'];
		$matrixPaymentType = (isset($matrixPayments[$matrixNumber])) ? $matrixPayments[$matrixNumber]['type'] : 0;
		$matrixPayment = (isset($matrixPayments[$matrixNumber])) ? $matrixPayments[$matrixNumber]['amount'] : 0;
		$balls = (isset($ballsList[$matrixNumber])) ? $ballsList[$matrixNumber][1] : 0;
		$date = ''; 
		$procedureInData = [$model->partner_id, $matrixNumber, $date, $matrixPaymentType, $matrixPayment, $balls, '@p1', '@p2', '@p3', '@p4', '@p5'];
		$procedureOutData = ['@p1'=>'VAR_OUT_RESULT', '@p2'=>'VAR_OUT_MATRIX', '@p3'=>'VAR_OUT_SPONSOR_ID', '@p4'=>'VAR_OUT_PARTNER_ID', '@p5'=>'VAR_OUT_RESULT2'];
		
		$dbModel = new DbBase();
		$procedureResult = $dbModel->callProcedure("reserve_binar_matrix_".$matrixNumber, $procedureInData, $procedureOutData);
		$outResult = $procedureResult['result'];
	
		if($outResult)
		{
			$matrixNumber = intval($procedureResult['output']['VAR_OUT_MATRIX']);
			
			if($matrixNumber > 0)
			{
				$sponsorID = intval($procedureResult['output']['VAR_OUT_SPONSOR_ID']);
				$partnerID = intval($procedureResult['output']['VAR_OUT_PARTNER_ID']);
				$outResult = $this->addPartnerInStructure($sponsorID, $partnerID, $matrixNumber, '', $demo);
			}
		}
		
		$result = $outResult;
		
		return $result;
	}
	
	public function createDemoStructure()
    {
		$result = false;
		$structureType = (isset(\Yii::$app->params['structure_type'])) ? \Yii::$app->params['structure_type'] : 0;
				
		if($structureType > 0)
		{
			$demo = (isset(\Yii::$app->params['demo_activation'])) ? \Yii::$app->params['demo_activation'] : false;
			$partnersCount = (isset(\Yii::$app->params['partners_count'])) ? \Yii::$app->params['partners_count'] : false;
			$partnersCount = ($partnersCount) ? Partners::find()->count() : 100;

			echo $partnersCount;
			var_dump($demo);

			$where = '';
			$where.= ($demo) ? 'demo_' : '';
			echo $where.'matrix'.'<br/>';
			$partnersList = ArrayHelper::index(Partners::find()->select(['id', 'sponsor_id', 'created_at'])->where([$where.'matrix_1' => 0])->limit($partnersCount)->orderBY('`created_at` ASC')->asArray()->all(), 'id');

			echo $partnersCount.' - partnersCount'.'<br/>';
			/*echo '<pre>';
			print_r($partnersList);
			echo '</pre>';*/

			if(!empty($partnersList))
			{
				$time_start = microtime(true);

				foreach($partnersList as $partnerID => $partnerData)
				{
					echo $partnerID.'<br/>';
					echo $partnerData['sponsor_id'].'<br/>';
					echo $partnerData['created_at'].'<br/>';

					if($structureType > 1)
					{
						$result = $this->addPartnerInStructure($partnerData['sponsor_id'], $partnerID, 1, 1, $partnerData['created_at'], $demo, 1);
						var_dump($result);
						if(!$result)
						{	echo 'Bug! - '.$partnerID.'<br/>';
							break;
						}
					}
					else
					{
						$model = (Partners::findOne($partnerID) !== null) ? Partners::findOne($partnerID) : null;
						$result = $this->activateLevelMatrix($model, true);

						if(!$result)
						{
							echo 'Bug! - '.$partnerID.'<br/>';
							break;
						}
					}
				}

				$time_end = microtime(true);

				//dividing with 60 will give the execution time in minutes other wise seconds
				$execution_time = ($time_end - $time_start);

				//execution time of the script
				echo '<b>Total Execution Time:</b> '.$execution_time.' seconds';
			}
		}
		
		return $result;
	}
	
	public function getStructureSponsorID($matrixID, $level, $number, $demo = false)
    {
		$demo = ($demo) ? 'demo_' : '';
		
		$sql = "SELECT `parent_id` as `partner_id`
		FROM `".$demo."matrix_".$number."`
		WHERE `id` = '".$matrixID."';";
		
		$connection = \Yii::$app->db;
		$result = $connection->createCommand($sql)->queryOne();
		
		return $result;
	}
	
	public function getStructureDataByPartnerID($partnerID, $demo = false)
    {
		$result = [];
		$demo = ($demo) ? 'demo_' : '';
		$matricesCount = (isset(Yii::$app->params['matrices_count'])) ? Yii::$app->params['matrices_count'] : 0;
		
		if($matricesCount > 0)
		{
			$sql = 'SELECT ';
			
			for($i=1; $i<=$matricesCount; $i++)
			{
				$sql.= '(
					SELECT COUNT(`'.$demo.'matrix_'.$i.'`.`id`)
					FROM `'.$demo.'matrix_'.$i.'`
					WHERE `'.$demo.'matrix_'.$i.'`.`partner_id` = '.$partnerID.'
				) AS `matrix_'.$i.'_count`'.(($i == $matricesCount) ? '' : ', ');
			}
		
			if($sql != '')
			{
				$connection = \Yii::$app->db;
				$result = $connection->createCommand($sql)->queryOne();
			}
		}
		
		return $result;
	}
	
	public static function getMatrixList($maxMatrix)
	{
		$result = [];
		
		for($i=1; $i<=$maxMatrix; $i++)
		{
			$result[$i] = Yii::t('form', 'Матрица'.'_'.$i);
		}
		
		return $result;
	}
	
	public static function getMatrixListInBonusMode($structureNumber, $id, $matrix)
	{
		$result = [];
		
		$select = self::getMatrixListSelectQuery($structureNumber, $matrix);
		
		if($select != '')
		{
			$sql = '';
			$where = self::getMatrixListWhereConditions($structureNumber, $matrix);
			$sql = 'SELECT '.$select.'
			FROM `partners`'.$where.'
			WHERE `partners`.`id` = '.$id.'
			GROUP BY `partners`.`id`;';
			
			if($sql != '')
			{
				$connection = \Yii::$app->db;
				$data = $connection->createCommand($sql)->queryOne();
				
				if(!empty($data))
				{
					foreach($data as $key=>$value)
					{
						if($value > 0)
						{
							$result[$key] = Yii::t('form', 'Матрица'.'_'.$key);
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function getMatrixListSelectQuery($structureNumber, $maxMatrix)
	{
		$result = '';
		
		if($maxMatrix > 0)
		{
			for($i=1; $i<=$maxMatrix; $i++)
			{
				$result.= 'COUNT(DISTINCT `matrix_'.$structureNumber.'_'.$i.'`.`id`) AS `'.$i.'`'.(($maxMatrix != $i) ?  ', ' : '');
			}
		}
		
		return $result;
	}
	
	public static function getMatrixListWhereConditions($structureNumber, $maxMatrix)
	{
		$result = '';
		
		if($maxMatrix > 0)
		{
			for($i=1; $i<=$maxMatrix; $i++)
			{
				$result.= 'LEFT JOIN `matrix_'.$structureNumber.'_'.$i.'` ON `matrix_'.$structureNumber.'_'.$i.'`.`partner_id` = `partners`.`id` AND `matrix_'.$structureNumber.'_'.$i.'`.`close_date` = 0 ';
			}
		}
		
		return $result;
	}
	
	public static function getMatricesSettings($structureNumber, $matrixNumber)
	{
		$connection = \Yii::$app->db;
		$sql = "SELECT * FROM `matrices_settings_".$structureNumber."` WHERE `number`='".$matrixNumber."'";
		$result = $connection->createCommand($sql)->queryOne();
		
		return $result;
	}
	
	public static function getAllMatricesSettings($structureNumber)
	{
		$connection = \Yii::$app->db;
		$sql = "SELECT * FROM `matrices_settings_".$structureNumber."`";
		$result = $connection->createCommand($sql)->queryAll();
		
		return $result;
	}
	
	public static function getAllMatricesSettingsInProject()
	{
		$result = [];
		$matricesSettingsList = [];
		$connection = \Yii::$app->db;
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
		if(!empty($structuresList))
		{
			foreach($structuresList as $structureNumber=>$data)
			{
				$sql = "SELECT * FROM `matrices_settings_".$structureNumber."`";
				$matricesSettingsList = $connection->createCommand($sql)->queryAll();
				
				if(!empty($matricesSettingsList))
				{
					$result[$structureNumber] = ArrayHelper::index($matricesSettingsList, 'number');
				}
			}
		}
		
		return $result;
	}
	
	public function getPaidPartnersList()
    {
		$result = Partners::find()
		->select('`partners`.`id`, `partners`.`login`, `partners`.`email`, `matrix_payments`.`amount` AS `matrix_payments_amount`, `matrix_payments`.`matrix_number` AS `matrix_number`, `matrix_payments`.`created_at` AS `paid_date`')
		->leftJoin('matrix_payments', '`matrix_payments`.`partner_id` = `partners`.`id`')
		->where('`matrix_payments`.`type` = 1 AND `matrix_payments`.`amount` > 0', [])
		->orderBy('`matrix_payments`.`created_at`');
		
		return $result;
	}
	
	public function changeAdmin($partnerID, $id, $structure, $number, $demo)
    {
		$result = false;
		$result = \Yii::$app->db->createCommand("UPDATE `".(($demo > 0) ? "demo_" : "")."matrix_".$structure."_".$number."` SET `partner_id` = ".$partnerID.", `change` = 1 WHERE `id`=:id")
		->bindValue(':id', $id)
		->execute();
		
		return $result;
	}
	
	public function setBonus($sponsorID, $partnerID, $structureNumber, $matrixNumber, $bonusAmount, $amount = 0, $root = false, $date = '', $demo = false, $status = 2, $reserve = 2)
    {
		$result = false;
		$demo = ($demo) ? 1 : 0;
		$date = ($date !== '') ? $date : time();
		$demoActivation = (isset(\Yii::$app->params['demo_structure_activation']) && (\Yii::$app->params['demo_structure_activation']) && !($reserve)) ? 1 : 0;
		
		$procedureInData = [$sponsorID, $partnerID, $structureNumber, $matrixNumber, $date, $demo, $status, $reserve, $demoActivation, $amount, $root, '@p1', '@p2'];
		
		if(!empty($procedureInData))
		{
			$dbModel = new DbBase();
			$procedureOutData = ['@p1'=>'VAR_OUT_RESULT', '@p2'=>'VAR_OUT_RESULT2'];
			$procedureResult = $dbModel->callProcedure('gold_token', $procedureInData, $procedureOutData);
			$result = $procedureResult['result'];
			
			if($result)
			{
				$matricesSettings = Matrix::getMatricesSettings($structureNumber, $matrixNumber);
				
				if(isset($matricesSettings['account_type']) && (Service::isActionAllowed('is_payment_allowed')))
				{	
					if($matricesSettings['account_type'] = '2')
					{
						if((isset(Yii::$app->params['payments_types'])))
						{
							$paymentModel = new Payment();
							$paymentType = Payment::getPaymentType();
							
							$matrixData = Matrix::getDataFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID);
							$matrixID = ($matrixData != null) ? $matrixData['id'] : 0;
								
							if($paymentModel->makePayment($partnerID, $matrixID, $structureNumber, $matrixNumber, $paymentType, $matricesSettings, $bonusAmount, true, true, true))
							{	
								$result = $paymentModel->activatePaymentByAccounting($structureNumber, $matrixNumber, $partnerID, $matricesSettings);
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function getStructureMatricesDropDownListModel($type)
    {
		$result = '';
		
		switch ($type) 
		{
			case 1:
				$result = 'ReserveForm';
				break;
			case 2:
				$result = 'SetMatrixForm';
				break;
		}
		
		return $result;
	}
}
