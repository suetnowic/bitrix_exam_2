<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler("main", "OnEpilog", [Open404::class, "log"]);

class Open404
{
	public static function log()
	{
		if(defined("ERROR_404") && ERROR_404 === "Y") {
			global $APPLICATION;
			CEventLog::Add(array(
		        "SEVERITY" => "INFO",
		        "AUDIT_TYPE_ID" => "ERROR_404",
		        "MODULE_ID" => "main",
		        "DESCRIPTION" => $APPLICATION->GetCurPage(),
		    ));

		}
	}
}