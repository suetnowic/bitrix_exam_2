<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(isset($arResult["CANONICAL"])) {
	$APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL"]);
}

if(isset($_REQUEST['REPORT']) && $_REQUEST['REPORT'] === "Y") {

	global $USER;
	$newsId = $_REQUEST['ID'];

	if($USER->IsAuthorized()) {
		$user = $USER->GetID() . "-" . $USER->GetLogin() . "-" . $USER->GetFullName(); 
	} else {
		$user = GetMessage("USER_NOT_AUTH");
	}

	$el = new CIBlockElement;
	$elId = $el->Add([
		"IBLOCK_ID" => 7,
		"ACTIVE" => "Y",
		"ACTIVE_FROM" => new Bitrix\Main\Type\DateTime(),
		"NAME" => GetMessage("REPORT", ["#ID#" => $newsId]),
		"PROPERTY_VALUES" => [
			"USER" => $user,
			"NEWS" => $newsId,
		],
	]);

	$msg = "";

	if($elId > 0) {
		$msg = GetMessage("RESPONSE_SUC", ["#ID#" => $elId]);
	} else {
		$msg = GetMessage("RESPONSE_NOT_SUC");
	}

	if ($arParams["REPORT_AJAX"] === "Y") {
		$APPLICATION->RestartBuffer();
		echo json_encode(["result" => $msg]);
		die();
	} else {
		echo "<script>
			history.pushState(null, null, '".$APPLICATION->GetCurPage()."');
			BX('report-msg').textContent='".$msg."';
		</script>";
	}
}