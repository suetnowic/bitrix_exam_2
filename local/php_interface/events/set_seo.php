<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler("main", "OnBeforeProlog", [Seo::class, "setSeoTags"]);

class Seo
{
	const METATAGS_IBLOCK_ID = 6;

	public static function setSeoTags()
	{
		global $APPLICATION;
		CModule::IncludeModule('iblock');

		$seoOrm = CIBlockElement::GetList(
			[],
			[
				"IBLOCK_ID" => self::METATAGS_IBLOCK_ID,
				"NAME" => $APPLICATION->GetCurPage(),
			],
			false,
			false,
			[
				"PROPERTY_SEO_TITLE", 
				"PROPERTY_SEO_DESCRIPTION"
			]
		);
		while($elem = $seoOrm->Fetch()) {
			$APPLICATION->SetPageProperty('title', $elem["PROPERTY_SEO_TITLE_VALUE"]);
			$APPLICATION->SetPageProperty('description', $elem["PROPERTY_SEO_DESCRIPTION_VALUE"]);
		}
	}
}