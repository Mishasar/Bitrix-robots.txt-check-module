<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

if (class_exists('saraykin_robots')) {
    return;
}

class saraykin_robots extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public function __construct()
    {
        $this->MODULE_ID = 'saraykin.robots';
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2018-08-15 00:00:00';
        $this->MODULE_NAME = Loc::getMessage('MODULE_ROBOTS_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_ROBOTS_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = "saraykin.pro";
        $this->PARTNER_URI = "";
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        CModule::IncludeModule($this->MODULE_ID);
        \Saraykin\Robots\AgentHelper::setAgent();
    }

    public function doUninstall()
    {
        CModule::IncludeModule($this->MODULE_ID);
        \Saraykin\Robots\AgentHelper::removeAgent();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }
}
