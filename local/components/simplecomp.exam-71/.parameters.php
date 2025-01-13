<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
		"CLASSIF_IBLOCK_ID" => [
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASSIF_IBLOCK_ID"),
			"TYPE" => "STRING",
		],
		"TEMPLATE_DETAIL_URL" => [
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_TEMPLATE_DETAIL_URL"),
			"TYPE" => "STRING",
		],
		"PROP_CODE" => [
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_PROPERTY_CODE"),
			"TYPE" => "STRING",
		],
		"CACHE_TIME"  =>  ["DEFAULT"=>36000000],
		"CACHE_GROUPS" => [
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		],
	),
);