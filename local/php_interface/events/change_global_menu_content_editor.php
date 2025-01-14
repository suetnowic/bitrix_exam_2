<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

AddEventHandler("main", "OnBuildGlobalMenu", [MenuGlobal::class, "change"]);

class MenuGlobal
{
	const GROUP = "content_editor";
	const GLOBAL_MENU = "global_menu_content";
	const MODULE_MENU_ITEMS = ["menu_iblock_/news"];

	public static function change(&$aGlobalMenu, &$aModuleMenu)
	{
		global $USER;
		$groupId = self::getGroupIdContentEditor();

		$arGroups = $USER->GetUserGroupArray();
		
		if($USER->IsAdmin()) {
			return true;
		}

		if(in_array($groupId, $arGroups)) {
			foreach ($aGlobalMenu as $key => $globalMenu) {
				if($key !== self::GLOBAL_MENU) {
					unset($aGlobalMenu[$key]);
				}
			}
			foreach ($aModuleMenu as $key => $moduleMenu) {
				if(!in_array($moduleMenu["items_id"], self::MODULE_MENU_ITEMS)) {
					unset($aModuleMenu[$key]);
				}
			}
		}
	}

	public static function getGroupIdContentEditor()
	{
		$rsGroups = CGroup::GetList(
			"",
			"",
			[
				"STRING_ID" => self::GROUP,
			]
		)->Fetch();
		return $rsGroups ? (int) $rsGroups["ID"] : 0;
	}
}