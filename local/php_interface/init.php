<?

include __DIR__ . "/events/change_global_menu_content_editor.php";

AddEventHandler("search", "BeforeIndex", array("MyClass", "BeforeIndexHandler"));

class MyClass
{
    const NEWS_IBLOCK_ID = 1;
    const ARRAY_IBLOCK_ID = [self::NEWS_IBLOCK_ID];

    public static function BeforeIndexHandler($arFields): array
    {
        if (
            $arFields["MODULE_ID"] === "iblock" &&
            in_array($arFields["PARAM2"], self::ARRAY_IBLOCK_ID)
        ) {
            CModule::IncludeModule("iblock");
            $rsNews = CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_ID" => self::NEWS_IBLOCK_ID,
                    "ID" => $arFields["ITEM_ID"],
                ],
                false,
                false,
                ["PREVIEW_TEXT"]
            );
            if ($news = $rsNews->GetNext()) {
                $arFields["TITLE"] = mb_substr(HTMLToTxt($news["PREVIEW_TEXT"]), 0, 50);
            }
        }
        return $arFields;
    }
}
