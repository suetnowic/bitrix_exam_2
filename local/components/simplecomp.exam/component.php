<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(
	intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0 &&
	intval($arParams["CLASSIF_IBLOCK_ID"]) > 0 &&
	!empty($arParams["UF_CODE"])
)
{
	if($this->StartResultCache()) {
		if(!Loader::includeModule("iblock"))
		{
			$this->AbortResultCache();
			ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
			return;
		}

		$arClassificator = [];
		$arSections = [];
		$arResult["COUNT"] = 0;

		$rsClassif = CIBlockSection::GetList(
			[],
			[
				"IBLOCK_ID" => $arParams["CLASSIF_IBLOCK_ID"],
				"ACTIVE" => "Y",
			],
			false,
			[
				"ID", "NAME",
			],
			false
		);
		while($arClassif = $rsClassif->GetNext()) {
			++$arResult["COUNT"];
			$arClassificator[$arClassif["ID"]] = $arClassif;
		}

		$rsSections = CIBlockSection::GetList(
			[],
			[
				"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
				"ACTIVE" => "Y",
			],
			false,
			[
				"ID", "NAME", $arParams["UF_CODE"]
			],
			false
		);
		while($arSection = $rsSections->GetNext()) {
			$arSections[$arSection["ID"]] = $arSection;
		}

		$rsProducts = CIBlockElement::GetList(
			[],
			[
				"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
				"ACTIVE" => "Y",
			],
			false,
			false,
			[
				"ID", "NAME", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "PROPERTY_PRICE", "IBLOCK_SECTION_ID"
			]
		);
		while($arElem = $rsProducts->GetNext()) {
			$sectionId = $arElem["IBLOCK_SECTION_ID"];
			$classifId = $arSections[$sectionId][$arParams["UF_CODE"]];

			if(!isset($arClassificator[$classifId]["SECTIONS"][$sectionId])) {
				$arClassificator[$classifId]["SECTIONS"][$sectionId] = $arSections[$sectionId];
			}
			$arClassificator[$classifId]["PRODUCTS"][] = $arElem;
		}

		$arResult["ITEMS"] = $arClassificator;

		$this->SetResultCacheKeys(["COUNT"]);

		$this->IncludeComponentTemplate();
	}

	$APPLICATION->SetTitle(GetMessage("TITLE", ["#COUNT#" => $arResult["COUNT"]]));
}

?>