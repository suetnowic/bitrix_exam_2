<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", [CheckProduct::class, "checkDeactivated"]);

class CheckProduct
{
	const SHOW_COUNT = 2;

	public static function checkDeactivated(&$arFields)
	{
		if($arFields["ACTIVE"] !== "Y") {

			CModule::IncludeModule("iblock");

			$product = CIBlockElement::GetList(
				[],
				[
					"IBLOCK_ID" => $arFields["IBLOCK_ID"],
					"ID" => $arFields["ID"]
				],
				false,
				false,
				["SHOW_COUNTER"]
			)->Fetch();

			if($product && $product["SHOW_COUNTER"] > self::SHOW_COUNT) {
				global $APPLICATION;
	            $APPLICATION->throwException(GetMessage("NON_SUCCESS_DEACTIV", ["#COUNT#" => $product["SHOW_COUNTER"]]));
	            return false;
			}
		}
	}
}