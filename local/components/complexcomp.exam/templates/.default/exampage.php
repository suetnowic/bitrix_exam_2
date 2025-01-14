<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
//Вывести значения переменных

echo "<pre>"; var_dump($arResult);

echo GetMessage("PARAM1", ["#PARAM1#" => $arResult["VARIABLES"]["PARAM1"]]); ?>
<br>
<?echo GetMessage("PARAM2", ["#PARAM2#" => $arResult["VARIABLES"]["PARAM2"]]);?>