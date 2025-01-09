<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arParams["CANONICAL"])) {

	$canonOrm = CIBlockElement::GetList(
		[],
		[
			"IBLOCK_ID" => $arParams["CANONICAl"],
			"PROPERTY_NEWS" => $arResult["ID"],
		],
		false,
		false,
		["ID", "NAME"]
	);
	while($item = $canonOrm->GetNext()) {
		$arResult['CANONICAl'] = $item["NAME"];
		$this->__component->SetResultCacheKeys(['CANONICAl']);
	}
}