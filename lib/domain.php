<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 08.12.19
 * Time: 19:59
 */

namespace Saraykin\Robots;


class Domain
{
    private $domain;
    private $rootDisallow;
    private $elementId;

    /**
     * Core constructor.
     *
     * @param string $url Домен сайта
     * @param int    $id  ID элемента
     */
    public function __construct(string $url, int $id)
    {
        $this->domain = $url;
        $this->elementId = $id;
        $file = file_get_contents($this->domain . 'robots.txt');

        if ($file) {
            $this->rootDisallow = !empty(preg_match('/Disallow: \/(\r\n|\r|\n)/', $file, $matches, PREG_OFFSET_CAPTURE));
        } else {
            $this->rootDisallow = false;
        }
    }

    /**
     * Возвращает проверку robots.txt
     *
     * @return bool
     */
    public function isDisallow(): bool
    {
        \CEventLog::Add(array(
            "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => "Проверка сайта",
            "MODULE_ID" => Storage::MODULE_NAME,
            "ITEM_ID" => $this->elementId,
            "DESCRIPTION" => "Результат проверки сайта " . $this->domain . ($this->rootDisallow ? ' положительниый' : ' отрицательный')
        ));

        return $this->rootDisallow;
    }

    /**
     * Возвращает URL домен
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->domain;
    }
}