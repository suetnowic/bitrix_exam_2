<?

function CheckUserCount()
{
	$timeLastCheck = COption::GetOptionString("main", "check_user");

	$admins = [];
	$newUsers = [];

	$arFilter = !empty($timeLastCheck) ? ["DATE_REGISTER_1" => $timeLastCheck] : [];

	$adminsOrm = CUser::GetList(
		"",
		"",
		[
			"GROUPS_ID" => [1],
		],
		[
			"FIELDS" => [
				"ID",
				"EMAIL",
			],
		]
	);
	while($arUser = $adminsOrm->GetNext()) {
		$admins[] = $arUser;
	}

	$usersOrm = CUser::GetList(
		["date_register" => "asc"],
		"id",
		$arFilter,
		[
			"FIELDS" => [
				"ID",
				"DATE_REGISTER",
			],
		]
	);
	while($arUser = $usersOrm->GetNext()) {
		$newUsers[] = $arUser;
	}

	if(!$timeLastCheck) {
		$timeLastCheck = $newUsers[0]["DATE_REGISTER"];
	}

	$obLastTime = new \Bitrix\Main\Type\DateTime($timeLastCheck);
	$obCurTime = new \Bitrix\Main\Type\DateTime();

	$diff = floor(($obCurTime->getTimestamp() - $obLastTime->getTimestamp()) / (60 * 60 * 24));

	\Bitrix\Main\Mail\Event::send([
		"EVENT_NAME" => "COUNT_REGITERED_USERS",
		'MESSAGE_ID' => 44,
		"LID" => "s1",
		"C_FIELDS" => [
	        "EMAIL_TO" => array_column($admins, "EMAIL"),
	        "COUNT" => count($newUsers),
	        "DAYS" => $diff,
	    ],
	]);

	\Bitrix\Main\Config\Option::set("main", "check_user", $obCurTime->toString());

	return __METHOD__ ."();";
}
