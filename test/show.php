<?php
$testData = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f',
			 'g','h','j','k','l','z','x','c','v','b','n','m','0','1',
			 '2','3','4','5','6','7','8','9','林','灿','斌','编','写',
			 '于','二','零','一','五','年','四','月','三','十','日');
if(isset($_GET['char']) && $_GET['char'] != null ){
	$char = $_GET['char'];
}else{
	$char = $testData[mt_rand(0,count($testData)-1)];
}
$outputSize = min(512, empty($_GET['size'])?36:intval($_GET['size']));
//Demo start

require(dirname(__FILE__) . "/src/avatar.php");
// composer
//require(__DIR__ . "/../vendor/autoload.php");
$flag = $_GET['cache'];
$avatarImg = new Md\Avatars($flag, $char, 2, $outputSize);

$avatarImg->outputBrowser();
/*$avatarImg->save('./avatars/Avatar256.png', 256);
$avatarImg->save('./avatars/Avatar128.png', 128);
$avatarImg->save('./avatars/Avatar64.png', 64);*/
$avatarImg->freeAvatar();