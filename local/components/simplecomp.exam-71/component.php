<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N" ? false: $USER->GetGroups()))) {

	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
		return;
	}

	if(
		intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0 &&
		intval($arParams["CLASSIF_IBLOCK_ID"]) > 0 &&
		!empty($arParams["TEMPLATE_DETAIL_URL"]) &&
		!empty($arParams["PROP_CODE"])
	) {

		if($this->StartResultCache(false, $USER->GetGroups())) {

			$arClassif = [];
			$arProducts = [];

			$rsClassif = CIBlockElement::GetList(
				[],
				[
					"IBLOCK_ID" => $arParams["CLASSIF_IBLOCK_ID"],
					"ACTIVE" => "Y",
				],
				false,
				false,
				[
					"ID", 
					"NAME"
				]
			);
			while($classif = $rsClassif->GetNext()) {
				$arResult["SECTION_QTY"]++;
				$arClassif[$classif["ID"]] = $classif;
			}

			$rsProducts = CIBlockElement::GetList(
				[
					"name" => "asc",
					"sort" => "asc",
				],
				[
					"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
					"ACTIVE" => "Y",
					"PROPERTY_FIRMA" => array_column($arClassif, "ID"),
				],
				false,
				false,
				[]
			);
			$rsProducts->setUrlTemplates($arParams["TEMPLATE_DETAIL_URL"]);
			while($product = $rsProducts->GetNextElement()) {
				$fields = $product->GetFields();
				$props = $product->GetProperties();

				foreach($arClassif as $key => $value) {
					$arClassif[$key]["PRODUCTS"][] = [
						"NAME" => $fields["NAME"],
						"DETAIL_URL" => $fields["DETAIL_PAGE_URL"],
						"MATERIAL" => $props["MATERIAL"]["VALUE"],
						"ARTNUMBER" => $props["ARTNUMBER"]["VALUE"],
						"PRICE" => $props["PRICE"]["VALUE"],
					];
				}
			}
			$arResult["ITEMS"] = $arClassif;
		}

 		$this->setResultCacheKeys(["SECTION_QTY"]);
	}

$this->includeComponentTemplate();

$APPLICATION->SetTitle(GetMessage("TITLE", ["#QTY#" => $arResult["SECTION_QTY"]]));

}
