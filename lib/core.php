<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 08.12.19
 * Time: 22:40
 */

namespace Saraykin\Robots;

use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Config\Option;


class Core
{
    public static function checkSitesHandler()
    {
        $openedDomainList = self::getOpenedSites();

        Event::sendImmediate([
            "EVENT_NAME" => "SARAYKIN_ROBOTS_CHECK_DOMAINS",
            "LID" => "s1",
            "C_FIELDS" => [
                "EMAIL_TO" => Option::get(Storage::MODULE_NAME, "EMAIL"),
                "BODY" => Helper::getRenderSiteList($openedDomainList),
            ],
        ]);

        return Storage::AGENT_FUNCTION;
    }

    /**
     * Возвращает незакрытые сайты от индексирования
     *
     * @return array
     */
    public static function getOpenedSites(): array
    {
        $openedDomainList = array_filter(self::getChecklistSites(), 'self::filterOpenSites');

        if (empty($openedDomainList)) {
            return [];
        }

        return $openedDomainList;
    }

    /**
     * Возвращает список доменов прошедших проверку
     *
     * @return array
     */
    public static function getChecklistSites(): array
    {
        $sitesList = self::getSitesList();

        if (empty($sitesList)) {
            return [];
        }

        return array_map('self::createDomainObject', $sitesList);
    }

    /**
     * Возвращает массив сайтов из инфоблока
     *
     * @return array
     */
    private static function getSitesList(): array
    {
        $sitesList = [];

        try {
            Loader::IncludeModule("iblock");

            $arSelect = [
                "ID",
                "IBLOCK_ID",
                "NAME"
            ];
            $arFilter = ["IBLOCK_ID" => Storage::IB_SITES];
            $res = \CIblockElement::GetList(["SORT" => "ASC"], $arFilter, false, false, $arSelect);

            while ($ob = $res->GetNextElement()) {
                $sitesList[] = $ob->GetFields();
            }
        } catch (\Exception $e) {
            AddMessage2Log($e->getMessage(), "saraykin.robots");
            Helper::sendError();
        }

        return $sitesList;
    }

    /**
     * Callback
     * Превращает массив сайта в объект DOMAIN
     *
     * @param $siteArray
     * @return Domain
     */
    private static function createDomainObject($siteArray): Domain
    {
        return new Domain($siteArray['NAME'], $siteArray['ID']);
    }

    /**
     * Callback
     * Проверяет домен на открытость
     *
     * @param Domain $domain
     * @return bool
     */
    private static function filterOpenSites(Domain $domain)
    {
        return !$domain->isDisallow();
    }
}