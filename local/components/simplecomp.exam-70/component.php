<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if($this->StartResultCache()) {

	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
		return;
	}

	if(
		intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0 &&
		intval($arParams["NEWS_IBLOCK_ID"]) > 0 &&
		!empty($arParams["UF_CODE"])
	) {

		$arSections = [];
		$arProducts = [];

		$rsSections = CIBlockSection::GetList(
			[],
			[
				"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
				"ACTIVE" => "Y",
			],
			false,
			[
				"ID", 
				"NAME", 
				$arParams["UF_CODE"],
			],
			false
		);
		while($sect = $rsSections->GetNext()) {
			$arSections[$sect["ID"]] = $sect;
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
				"ID",
				"NAME",
				"IBLOCK_SECTION_ID",
				"PROPERTY_ARTNUMBER",
				"PROPERTY_PRICE",
				"PROPERTY_MATERIAL",
			]
		);
		while($product = $rsProducts->GetNext()) {
			$arResult["PRODUCT_QTY"]++;
			$arProducts[$product["IBLOCK_SECTION_ID"]][] = [
				"NAME" => $product["NAME"],
				"ARTNUMBER" => $product["PROPERTY_ARTNUMBER_VALUE"],
				"MATERIAL" => $product["PROPERTY_MATERIAL_VALUE"],
				"PRICE" => $product["PROPERTY_PRICE_VALUE"],
			];
		}

		$rsNews = CIBlockElement::GetList(
			[],
			[
				"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
				"ACTIVE" => "Y"
			],
			false,
			false,
			[
				"ID", 
				"NAME", 
				"ACTIVE_FROM",
			]
		);
		while($news = $rsNews->GetNext()) {
			$item = $news;
			$item["PRODUCTS"] = [];
			$item["SECTIONS"] = [];
			foreach ($arSections as $sectionId => $arSect) {
				if(in_array($news["ID"], $arSect[$arParams["UF_CODE"]])) {
					$item["SECTIONS"][] = $arSect["NAME"];
					$item["PRODUCTS"] = array_merge($item["PRODUCTS"], $arProducts[$sectionId]);
				}
			}
			$arResult["ITEMS"][] = $item;
		}
	}

	$this->SetResultCacheKeys([]);

	$APPLICATION->SetTitle(GetMessage("TITLE_COUNT_PRODUCT", ["#COUNT#" => $arResult["PRODUCT_QTY"]]));

	$this->includeComponentTemplate();
	
}
?>