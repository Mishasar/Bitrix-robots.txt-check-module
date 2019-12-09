<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 08.12.19
 * Time: 22:54
 */

namespace Saraykin\Robots;


class Helper
{
    /**
     * Возвращает HTML список сайтов
     *
     * @param array $domainList
     * @return string
     */
    public static function getRenderSiteList(array $domainList): string
    {
        $html = 'По результатам проверки следующие сайты не закрыты от индексации:<br>';
        $html .= '<ul>';

        foreach ($domainList as $site) {
            $html .= '<li>' . $site->getName() . '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Отправка статуса ошибки
     */
    public static function sendError() {
        Event::sendImmediate([
            "EVENT_NAME" => "SARAYKIN_ROBOTS_CHECK_DOMAINS",
            "LID" => "s1",
            "C_FIELDS" => [
                "EMAIL_TO" => Option::get(Storage::MODULE_NAME, "EMAIL"),
                "BODY" => 'Произошла ошибка',
            ],
        ]);
    }
}