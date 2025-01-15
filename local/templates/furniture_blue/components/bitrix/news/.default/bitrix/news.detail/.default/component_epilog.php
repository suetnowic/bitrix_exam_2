<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(isset($arResult["CANONICAl"])) {
	$APPLICATION->SetPageProperty('canonical', $arResult["CANONICAl"]);
}

if(isset($_REQUEST['REPORT']) && $_REQUEST['REPORT'] === "Y") {

	$newsId = $arResult["ID"];

	global $USER;

	if($USER->IsAuthorized()) {
		$user = $USER->ID . "-" . $USER->GetLogin() . "-" . $USER->GetFullName();
	} else {
		$user = GetMessage("USER_NOT_AUTH");
	}

	$el = new CIBlockElement;
	$arProperty = [
		"IBLOCK_ID" => 7,
		"ACTIVE_FROM" => new Bitrix\Main\Type\DateTime(),
		"NAME" => GetMessage("REPORT", ["#REPORT_ID#" => $newsId]),
		"PROPERTY_VALUES" => [
			"USER" => $user,
			"NEWS" => $newsId
		],
	];
	$id = $el->Add($arProperty);

	$msg = "";

	if($id > 0) {
		$msg = GetMessage("REPORT_SUCCESS", ["#ID#" => $id]);
	} else {
		$msg = GetMessage("REPORT_ERROR");
	}

	if ($arParams["REPORT_AJAX"] === "Y") {
		$APPLICATION->RestartBuffer();
		echo json_encode(["content" => $msg]);
		die;
	} else {
		echo "<script>
			history.pushState(null, null, '".$APPLICATION->GetCurPage()."');
			BX('report_msg').textContent='".$msg."';
		</script>";
	}
}