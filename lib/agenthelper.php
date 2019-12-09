<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 08.12.19
 * Time: 22:54
 */

namespace Saraykin\Robots;

use Bitrix\Main\Config\Option;

class AgentHelper
{
    public static function setAgent(int $interval = 1)
    {
        \CAgent::AddAgent(
            Storage::AGENT_FUNCTION,
            Storage::MODULE_NAME,
            "N",
            $interval * 3600
        );
    }

    public static function removeAgent()
    {
        \CAgent::RemoveAgent(
            Storage::AGENT_FUNCTION,
            Storage::MODULE_NAME
        );
    }

    public static function rebuildAgent()
    {
        self::removeAgent();
        self::setAgent(Option::get(Storage::MODULE_NAME, "frequency"));
    }
}