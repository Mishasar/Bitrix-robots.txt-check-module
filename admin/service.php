<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php";

$moduleId = "bitrix.module";
if ($APPLICATION->GetGroupRight($moduleId) == "D") {
    $APPLICATION->AuthForm("Не достаточно прав");
}

//
// process code
//

require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_after.php";
