<?php

$startMemory = memory_get_usage();
$array = range(1, 100000);

 include 'PHPExcel-1.8/Classes/PHPExcel.php';
$objPHPExcel = PHPExcel_IOFactory::load("testexcel.xls");

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//echo '<pre>';print_r($sheetData);
$english = "Your assigned code reviews";
$rus = "Оценки ваших назначенных кодов";

$arr1 = ['Оценки','ваших','назначенных','кодов'];
$arr2 = ['Your','assigned','code','reviews'];


if(count($sheetData) >0)
{
	$i = 0;
	foreach($sheetData as $rowId => $rowData)
	{
		foreach ($rowData as $key => $value) 
		{
			$oldValue = $value;
			$newValue = str_replace($arr1,$arr2,$oldValue,$j);
			$i = $i+$j;
			if($newValue!='')
			{
				//echo $rowId.'--'.$key;die;	
			}
			
			$sheetData[$rowId][$key] = $newValue;
		}
	}
}
$inputFileType = 'Excel5';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$worksheetNames = $objReader->listWorksheetNames("testexcel.xls");
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body>
<textarea rows="10" cols="100"><?php print_r($sheetData);?></textarea>
<br/>
Replacements : <input type="text" value="<?php echo $i;?>">
<?php 
echo '<h3>Worksheet Names</h3>';
echo '<ol>';
foreach ($worksheetNames as $worksheetName) {
	echo '<li>', $worksheetName, '</li>';
}
echo '</ol>';


echo (memory_get_usage() - $startMemory) / (1024*1024), ' MB';
?>
</body>
</html>