<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", [CheckTextWhenUpdate::class, "change"]);

class CheckTextWhenUpdate
{

	const IBLOCK_NEWS_ID = 1;
	const SUBSTRING = "калейдоскоп";
	const REPLACE_SUBSTR = "[...]";

	public static function change(&$arFields)
	{
		if((int)$arFields["IBLOCK_ID"] == self::IBLOCK_NEWS_ID) {
			if(str_contains($arFields["PREVIEW_TEXT"], self::SUBSTRING)) {
				$arFields["PREVIEW_TEXT"] = str_replace(self::SUBSTRING, self::REPLACE_SUBSTR, $arFields["PREVIEW_TEXT"]);

				CEventLog::Add([
					"SEVERITY" => "INFO",
			        "AUDIT_TYPE_ID" => GetMessage("CHANGE_SUBSTRING_AUDIT_TYPE"),
			        "MODULE_ID" => "iblock",
			        "ITEM_ID" => $arFields['ID'],
			        "DESCRIPTION" => GetMessage("CHANGE_SUBSTRING_DESCR", ["#ID#" => $arFields['ID']]),
				]);
			}
		}
		
	}
}
