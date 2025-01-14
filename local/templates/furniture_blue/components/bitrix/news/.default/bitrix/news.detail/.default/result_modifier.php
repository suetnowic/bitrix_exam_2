<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(isset($arParams["CANONICAL"])) {
	$rsElement = CIBlockElement::GetList(
		[],
		[
			"IBLOCK_ID" => $arParams["CANONICAL"],
			"PROPERTY_NEWS" => $arResult["ID"],
		],
		false,
		false,
		[
			"ID",
			"NAME",
		]
	)->Fetch();
	if($rsElement) {
		$arResult["CANONICAL"] = $rsElement["NAME"];
		$this->__component->SetResultCacheKeys(["CANONICAL"]);
	}
}