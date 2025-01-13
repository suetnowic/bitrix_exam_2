<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	$this->abortResultCache();
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

global $USER;

if(
	intval($arParams["NEWS_IBLOCK_ID"]) > 0 &&
	!empty($arParams["PROP_CODE"]) &&
	!empty($arParams["UF_CODE"])
) {
	if($USER->IsAuthorized() && $this->startResultCache(false, $USER->GetID())) {
		$arUsers = [];
		$arUsersGroupType = [];
		$arrNews = [];
		$arNewsId = [];

		$rsUsers = CUser::GetList(
			"", 
			"",
			[
				"!" . $arParams["UF_CODE"] => false
			],
			[
				"SELECT" => [
					$arParams["UF_CODE"], 
				],
				"FIELDS" => [
					"ID", 
					"LOGIN"
				],
			]
		);
		while($arUser = $rsUsers->GetNext()) {
			if((int)$arUser["ID"] === (int)$USER->GetID()) {
				$currentUserType = (int)$arUser[$arParams["UF_CODE"]];
			}
			$arUsers[$arUser[$arParams["UF_CODE"]]][$arUser["ID"]] = $arUser;
		}

		$arUsersCurrentType = $arUsers[$currentUserType];

		$rsNews = CIBlockElement::GetList(
			[],
			[
				"ACTIVE" => "Y",
				"PROPERTY_" . $arParams["PROP_CODE"] => array_column($arUsersCurrentType, "ID"),
			],
			false,
			false,
			[]
		);
		while($arNews = $rsNews->GetNextElement()) {
			$arFields = $arNews->getFields();
			$arProps = $arNews->getProperties();

			if(!in_array($USER->GetID(), $arProps[$arParams["PROP_CODE"]]["VALUE"])) {
				foreach ($arProps[$arParams["PROP_CODE"]]["VALUE"] as $author) {
					if(array_key_exists($author, $arUsersCurrentType)) {
						$arNewsId[] = $arFields["ID"];
						$arrNews[$author]["ID"] = $author;
						$arrNews[$author]["LOGIN"] = $arUsersCurrentType[$author]["LOGIN"];
						$arrNews[$author]["NEWS"][] = [
							"ID" => $arFields["ID"],
							"NAME" => $arFields["NAME"],
							"ACTIVE_FROM" => $arFields["ACTIVE_FROM"],
						];
					}
				}
			}
		}

 		$arResult["NEWS_QTY"] = count(array_unique($arNewsId));
		$arResult["ITEMS"] = $arrNews;

		$this->SetResultCacheKeys(["NEWS_QTY"]);

		$APPLICATION->SetTitle(GetMessage("TITLE", ["#QTY#" => $arResult["NEWS_QTY"]]));
	}
	
	$this->includeComponentTemplate();
}
