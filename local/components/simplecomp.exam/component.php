<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

global $USER;

$arButtons = CIBlock::GetPanelButtons($arParams["NEWS_IBLOCK_ID"]);

$arIcons[] = array(
	"URL" => $arButtons["submenu"]["element_list"]["ACTION_URL"],
	"TITLE" => GetMessage('IB_IN_ADMIN'),
	"IN_PARAMS_MENU" => true
);

$this->AddIncludeAreaIcons($arIcons);

if($this->StartResultCache(false, $USER->GetID())) {
	if(!Loader::includeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
		return;
	}

	if(
		intval($arParams["NEWS_IBLOCK_ID"]) > 0 &&
		!empty($arParams["PROP_CODE"]) &&
		!empty($arParams["UF_CODE"])
	) {

		$arUsers = [];
		$curUserType = 0;
		$arUsersCurType = [];
		$arNews = [];
		$newsQty = 0;

		$rsUsers = CUser::GetList(
			"",
			"",
			[
				"ACTIVE" => "Y",
				"!" . $arParams["UF_CODE"] => false,
			],
			[
				"SELECT" => [
					$arParams["UF_CODE"]
				],
				"FIELDS" => [
					"ID",
					"LOGIN",
				]
			]
		);
		while ($arUser = $rsUsers->GetNext()) {
			if((int)$arUser["ID"] == (int)$USER->GetID()) {
				$curUserType = $arUser[$arParams["UF_CODE"]];
			}
			$arUsers[$arUser[$arParams["UF_CODE"]]][$arUser["ID"]] = $arUser;
		}

		$arUsersCurType = $arUsers[$curUserType];

		$res = CIBlockElement::GetList(
			[],
			[
				"ACTIVE" => "Y",
				"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
				'PROPERTY_' . $arParams["PROP_CODE"] => array_column($arUsersCurType, "ID"),
			],
			false,
			false,
			[]
		);
		while($arElem = $res->GetNextElement()) {
			$arFields = $arElem->getFields();
			$arProps = $arElem->getProperties();

			
			
			if(!in_array($USER->GetID(), $arProps[$arParams["PROP_CODE"]]["VALUE"])) {
				$newsQty++;
				foreach ($arProps[$arParams["PROP_CODE"]]["VALUE"] as $author) {
					if(array_key_exists($author, $arUsersCurType)) {
						$arNews[$author]["ID"] = $author;
						$arNews[$author]["LOGIN"] = $arUsersCurType[$author]["LOGIN"];
						$arNews[$author]["NEWS"][] = [
							"ID" => $arFields['ID'],
							"NAME" => $arFields["NAME"],
							"ACTIVE_FROM" => $arFields["ACTIVE_FROM"]
						]; 
					}
				}
			}
		}

		$arResult["ITEMS"] = $arNews;
		$arResult["NEWS_QTY"] = $newsQty;

		$this->SetResultCacheKeys(["NEWS_QTY"]);
		
	}

	$this->includeComponentTemplate();
	$APPLICATION->SetTitle(GetMessage("TITLE", ["#COUNT#" => $arResult["NEWS_QTY"]]));
}

?>