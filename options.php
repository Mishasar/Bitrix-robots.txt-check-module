<?
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'saraykin.robots');

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Saraykin\Robots\AgentHelper;

Loader::includeModule(ADMIN_MODULE_NAME);
Loc::loadMessages(__FILE__);

$moduleRight = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);
$request = HttpApplication::getInstance()->getContext()->getRequest();
$moduleId = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
Loader::includeModule($moduleId);

$aTabs = array(
    array(
        "DIV" => "edit",
        "TAB" => Loc::getMessage(" _OPTIONS_TAB_NAME"),
        "TITLE" => Loc::getMessage("MODULE_ROBOTS_OPTIONS_TAB_NAME"),
        "OPTIONS" => array(
            Loc::getMessage("MODULE_ROBOTS_TAB_COMMON"),
            array(
                "EMAIL",
                Loc::getMessage("MODULE_ROBOTS_EMAIL"),
                "saraykin1996@gmail.com",
                array("text", 50)
            ),
            array(
                "frequency",
                Loc::getMessage("MODULE_ROBOTS_FREQ"),
                "2",
                array("text", 5)
            )
        )
    )
);

$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();

?>
    <form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($moduleId); ?>&lang=<? echo(LANG); ?>"
          method="post">

        <?
        foreach ($aTabs as $aTab) {

            if ($aTab["OPTIONS"]) {

                $tabControl->BeginNextTab();

                __AdmSettingsDrawList($moduleId, $aTab["OPTIONS"]);
            }
        }

        $tabControl->Buttons();
        ?>

        <input type="submit" name="apply" value="<? echo(Loc::GetMessage("MODULE_ROBOTS_OPTIONS_INPUT_APPLY")); ?>"
               class="adm-btn-save"/>
        <input type="submit" name="default"
               value="<? echo(Loc::GetMessage("MODULE_ROBOTS_OPTIONS_INPUT_DEFAULT")); ?>"/>

        <?
        echo(bitrix_sessid_post());
        ?>

    </form>

<?php
$tabControl->End();

if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) {

        foreach ($aTab["OPTIONS"] as $arOption) {

            if (!is_array($arOption)) {

                continue;
            }

            if ($arOption["note"]) {

                continue;
            }

            if ($request["apply"]) {

                $optionValue = $request->getPost($arOption[0]);

                if ($arOption[0] == "switch_on") {

                    if ($optionValue == "") {

                        $optionValue = "N";
                    }
                }

                Option::set($moduleId, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            } elseif ($request["default"]) {

                Option::set($moduleId, $arOption[0], $arOption[2]);
            }
        }
    }

    AgentHelper::rebuildAgent();

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $moduleId . "&lang=" . LANG);
}
?>