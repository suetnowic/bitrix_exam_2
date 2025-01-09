<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler("main", "OnBeforeEventAdd", [ChangeData::class, "change"]);

class ChangeData
{

	const EVENT = "FEEDBACK_FORM";

	public static function change(&$event, &$lid, &$arFields)
	{
		if($event === self::EVENT) {
			global $USER;

			if($USER->IsAuthorized()) {
				// Пользователь авторизован: id (логин) имя, данные из формы: Имя пользователя
				$arFields["AUTHOR"] = GetMessage(
					"AUTH_USER", 
					[
						"#ID#" => $USER->GetID(),
						"#LOGIN#" => $USER->GetLogin(),
						"#NAME#" => $USER->GetFullName(),
						"#AUTHOR#" => $arFields["AUTHOR"]
					]
				);
			} else {
				// Пользователь не авторизован, данные из формы: Имя пользователя
				$arFields["AUTHOR"] = GetMessage(
					"NOT_AUTH_USER",
					[
						"#AUTHOR#" => $arFields["AUTHOR"]
					]
				);
			}

			CEventLog::Add(
				[
					"SEVERITY" => "SECURITY",
			        "AUDIT_TYPE_ID" => GetMessage("AUDIT_TYPE"),
			        "MODULE_ID" => "main",
			        "DESCRIPTION" => GetMessage("DESCRIPTION", ["#AUTHOR#" => $arFields["AUTHOR"]])
				]
			);
		}
	}
}