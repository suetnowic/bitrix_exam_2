<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
		"CLASSIF_IBLOCK_ID" => [
			"NAME" => GetMessage("CLASSIF_IBLOCK_ID"),
			"TYPE" => "STRING",
		],
		"UF_CODE" => [
			"NAME" => GetMessage("UF_CODE"),
			"TYPE" => "STRING",
		],
		"CACHE_TIME"  =>  ["DEFAULT"=>36000000],
	),
);