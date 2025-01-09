<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler("main", "OnBuildGlobalMenu", [ChangeGlobalMenu::class, "changeGMContentEditor"]);

class ChangeGlobalMenu
{

	const GROUP_CODE = "content_editor";
	const MENU_CONTENT = "global_menu_content";
	const MODULE_MENU = ["menu_iblock_/news"];

	public static function changeGMContentEditor(&$aGlobalMenu, &$aModuleMenu)
	{
		global $USER;
		if($USER->IsAdmin()) {
			return true;
		}

		$groupId = self::getContentEditorGroupId();
		if($groupId > 0 && in_array($groupId, $USER->GetUserGroup($USER->GetID()))) {
			foreach ($aGlobalMenu as $key => $globalMenu) {
				if($key !== self::MENU_CONTENT) {
					unset($aGlobalMenu[$key]);
				}
			}
			foreach ($aModuleMenu as $key => $moduleMenu) {
				if(!in_array($moduleMenu["items_id"], self::MODULE_MENU)) {
					unset($aModuleMenu[$key]);
				}
			}
		}
	}

	public static function getContentEditorGroupId(): int
	{
		$groupOrm = CGroup::GetList(
			"",
			"",
			[
				"STRING_ID" => self::GROUP_CODE
			],
			false
		);
		while($group = $groupOrm->Fetch()) {
			return $group ? (int)$group["ID"] : 0;
		}
	}
}