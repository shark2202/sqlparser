<?php 

//extension=sqlparser

use Sqlparser\Sqlparser;

$max = 10*10000;

$s = time();
echo $max, '====>';
while ($max > 0) {
	$sql = 'select a from b where c = 100 and dd ='.$max;
	$json1 = Sqlparser::sql2json($sql);
	//echo $json1,PHP_EOL;

	$max = $max - 1;
}
$d = time() - $s;
echo $d,PHP_EOL;