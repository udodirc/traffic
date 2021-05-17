<?php

namespace common\models;

use Yii;

class DbBase extends \yii\db\ActiveRecord
{
	public function callProcedure($procedureName, $procedureInData = [], $outPutData = [])
    {
		$result = ['result'=>false, 'output'=>[]];
		$outResult = false;
		$procedureResult = [];
		$sqlPocedure = self::getSqlPocedure($procedureName, $procedureInData, $outPutData);
		
		if($sqlPocedure['procedure'] != '')
		{	$connection = \Yii::$app->db;
			$command = $connection->createCommand($sqlPocedure['procedure']);
			$res = $command->execute();
                        $command->pdoStatement->closeCursor();
			$procedureResult = ($sqlPocedure['output'] != '') ? $connection->createCommand($sqlPocedure['output'])->queryAll() : $connection->createCommand("SELECT @p1 AS `VAR_OUT_RESULT`")->queryAll();
			$outResult = filter_var($procedureResult[0]['VAR_OUT_RESULT'], FILTER_VALIDATE_BOOLEAN);
			$result = ['result'=>$outResult, 'output'=>(!empty($outPutData)) ? $procedureResult[0] : []];
		}
		
		return $result;
	}
	
	public static function getSqlPocedure($procedureName, $procedureInData = [], $outPutData = [])
    {
		$result = ['procedure'=>'', 'output'=>''];
		$procedure = '';
		$outPut = '';
		
		if(!empty($procedureInData))
		{
			$procedure = "CALL `".$procedureName."` (";
			$count = count($procedureInData);
			$data = '';
			$comma = '';
			
			foreach($procedureInData as $i => $inData)
			{
				$comma = (($i == ($count - 1)) ? "" : ", ");
				
				if(!is_object($inData))
				{	
                                    $procedure.= (preg_match("/(@p)/", $inData)) ? $inData.$comma : "'".$inData."'".$comma;
                                }
			}
		
			$procedure.=")";
		}
		
		if(!empty($outPutData))
		{
			$outPut = 'SELECT ';
			$count = count($outPutData);
			$i = 1;
			
			foreach($outPutData as $var => $inData)
			{
				$outPut.=$var." AS `".$inData."`".(($i == $count) ? "" : ", ");
				$i++;
			}
		}
		
		return $result = ['procedure'=>$procedure, 'output'=>$outPut];
	}
}
