<?php 

mysql_connect('localhost',root,'') or die('error');
mysql_select_db('ci_adminlte') or die('Error 1');
/*---------------------------------------------------------*/
/*$dicId = 10;
$filePath = dirname(__FILE__)."/temp/sample.csv";
$filePath = "/tmp/sample.csv";
echo '>>',$loadSql ="LOAD DATA LOCAL INFILE '$filePath' INTO TABLE ori
FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n'
(lan1,lan2) SET dicId = $dicId;";

mysql_query($loadSql) or die(mysql_error());
die;*/
/*---------------------------------------------------------*/
// echo $filePath;die;
/*if(file_exists($filePath)){unlink($filePath);}
$fp = fopen($filePath, 'w') or die(print_r(error_get_last()));

for ($i = 0;$i <= 1000;$i++) {
	$fields = array(generateRandomString(5),generateRandomString(10));
    fputcsv($fp, $fields);
}

fclose($fp);


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
*/
/*---------------------------------------------------------*/







die;
$startMemory = memory_get_usage();
$array = range(1, 100000);
$array1 = range(1, 100000);

$usage = (memory_get_usage() - $startMemory)/(1024*1024);
echo $usage, ' Mb';



die;
$arrTest1 = array();
echo "Hello world";
if(in_array(52,$arrTest))
{
    echo "Found";
}
?>