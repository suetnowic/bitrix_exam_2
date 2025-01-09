<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(isset($arResult["CANONICAl"])) {
	$APPLICATION->SetPageProperty('canonical', $arResult["CANONICAl"]);
}