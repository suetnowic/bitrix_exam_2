<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if(!isset($arParams["PRODUCTS_IBLOCK_ID"])) {
	$arParams["PRODUCTS_IBLOCK_ID"] = 2;
}

global $USER;

$arIcons[] = array(
	"URL" =>$APPLICATION->GetCurPageParam("hello=world", ["hello"]),
	"TITLE"	=> GetMessage('HELLO_WORLD'),
);

$this->AddIncludeAreaIcons($arIcons);

if(
	intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0 &&
	!empty($arParams["PROP_CODE"])
)
{
	if($USER->IsAuthorized() && $this->StartResultCache(false, $USER->GetID())) {
		$arUsers = [];
		$arUserProducts = [];
		$arResult["FAV_CUR_USER"] = [];

		$rsUsers = CUser::GetList(
			"",
			"",
			[],
			[
				"FIELDS" => ["ID", "LOGIN"] 
			]
		);
		while($user = $rsUsers->GetNext()) {
			$arUsers[$user["ID"]] = $user;
		}

		$rsProducts = CIBlockElement::GetList(
			[],
			[
				"!PROPERTY_" . $arParams["PROP_CODE"] => false,
			],
			false,
			false,
			[]
		);
		while($product = $rsProducts->GetNextElement()) {
			$fields = $product->GetFields();
			$props = $product->GetProperties();

			foreach($arUsers as $user) {
				if(in_array($user["ID"], $props[$arParams["PROP_CODE"]]["VALUE"])) {
					$arUsers[$user["ID"]]["PRODUCTS"][] = [
						"ID" => $fields["ID"],
						"NAME" => $fields["NAME"],
						"MATERIAL" => $props["MATERIAL"]["VALUE"],
						"PRICE" => $props["PRICE"]["VALUE"],
						"ARTNUMBER" => $props["ARTNUMBER"]["VALUE"],
					];
				}
			}
		}
		$arResult["FAV_CUR_USER"] = $arUsers[$USER->GetID()];
		$arResult["COUNT"] = count($arUsers[$USER->GetID()]["PRODUCTS"]);

		unset($arUsers[$USER->GetID()]);

		$arRecomend = [];
		$favIds = array_column($arResult["FAV_CUR_USER"]["PRODUCTS"], 'ID');
		foreach($arUsers as $userId => $user) {
			if (!isset($user["PRODUCTS"])) {
				continue;
			}
			$relFavIds = array_column($user["PRODUCTS"], 'ID');
			$intersect = array_intersect($favIds, $relFavIds);
			if ($intersect) {
				$recommendProductIds = [];
				$recommendProductIds = array_unique(array_merge($recommendProductIds, array_diff($relFavIds, $favIds)));
				foreach ($recommendProductIds as $id) {
					$key = array_search($id, $relFavIds);
					
					if (!isset($arRecomend[$id])) {
						$arRecomend[$id] = $user["PRODUCTS"][$key];
						$arRecomend[$id]['USERS'][$userId] = $arUsers[$userId]['LOGIN'];
					}
					else {
						$arRecomend[$id]['USERS'][$userId] = $arUsers[$userId]['LOGIN'];	
					}

				}
			}

		}
		$arResult["RECOMM"] = $arRecomend;

		$this->SetResultCacheKeys(["COUNT"]);

		$this->includeComponentTemplate();

		$APPLICATION->SetTitle(GetMessage("FAV_COUNT", ["#COUNT#" => $arResult["COUNT"]]));
	}
	
}