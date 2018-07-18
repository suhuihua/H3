<?php 


$status=['activated','rejected','held'];
$a = 4648;
$j=0;
for ($i=0; $i < 1000; $i++) {

$con = "insert into comments values(null, '苏', 'www.qq.com', '2018-07-19 15:40:04', '进与去此会历合新原所积和名先的。物常规查完想见完系米党平每做心该往同。属变已路则该却解育统结月个必。象外比根局规格号意强包她名华任持决。那严社分名史情局火个酸给却织。于部层领便元可发圆产例步会处。才但众命级当现往等置都众支具认。公与林治土能海长军将调走石。小件利流易能及小断员但等下前光做。万定带党强音装林民原包主往把近。音己几必持那别还构格没与离厂月社。', '{$status[$j]}', {$a}, 97);";
$a++;
$j++;
if ($j==3) {
	$j=0;
}
$filename="./a.txt";
$handle=fopen($filename,"a+");
$str=fwrite($handle,"{$con}\n");
fclose($handle);
}