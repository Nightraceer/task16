<?php

namespace Main\Util;


use Doctrine\ORM\EntityManager;
use Main\Entity\BillsRuEvents;

/**
 * Class Parser
 * @package Main\Util
 */
class Parser
{
    /**
     * Инициализированный curl
     *
     * @var
     */
    protected $_curl;

    /**
     * Список действий
     *
     * @var array
     */
    protected $_log = [];

    /**
     * @var EntityManager
     */
    protected $_entityManager;

    /**
     * Ссылка на ресурс
     *
     * @var string
     */
    public $url = 'https://www.bills.ru/';

    /**
     * Parser constructor.
     * @param EntityManager $entityManager
     * @param string $url
     */
    public function __construct(EntityManager $entityManager, $url = '')
    {
        if ($url) {
            $this->url = $url;
        }

        $this->_entityManager = $entityManager;
    }

    /**
     * Запуск парсинга
     */
    public function run()
    {
        $this->setLog("Начинаем парсинг");
        $content = $this->getContent($this->url);

        if ($content) {
            $pattern = '/<table\s+id="bizon_api_news_list"\s+class="bizon_api_list\s+bizon_api_news_table"\s*>(.*?)<\/table>/uis';
            preg_match($pattern, $content, $matches);

            if (isset($matches[1])) {
                $this->setLog("Найден блок с новостями");

                $pattern = '/<tr\s+class="bizon_api_news_row"\s*>(.*?)<\/tr>/uis';
                preg_match_all($pattern, $matches[1], $matchesRow);

                $counter = 0;

                if (isset($matchesRow[0])) {

                    $this->setLog("Найдены отдельные новости");

                    foreach ($matchesRow[0] as $row) {

                        $patternUrlTitle = '/<a\s+?href="(.*?)"\s*>(.*?)<\/a>/uis';
                        $patternDate = '/<td\s+class="news_date"\s*>(.*?)<\/td>/uis';

                        preg_match($patternUrlTitle, $row, $matchesUrlTitle);
                        preg_match($patternDate, $row, $matchesDate);

                        $url = isset($matchesUrlTitle[1]) ? trim($matchesUrlTitle[1]) : false;
                        $title = isset($matchesUrlTitle[2]) ? trim($matchesUrlTitle[2]) : false;
                        $date = isset($matchesDate[1]) ? $this->prepareDate($matchesDate[1]) : false;

                        if ($url && $title && $date) {
                            $counter++;
                            $this->setLog("Найдены ссылка, заголовок и дата публикации новости № " . $counter);

                            $bill = $this->_entityManager->getRepository('Main\Entity\BillsRuEvents')->findBy(['url' => $url]);

                            if (!$bill) {
                                $this->setLog("Начинаем создание записи");

                                $bill = new BillsRuEvents([
                                    'title' => $title,
                                    'url' => $url,
                                    'date' => $date
                                ]);

                                $this->_entityManager->persist($bill);
                                $this->_entityManager->flush();

                                $this->setLog("Запись № " . $counter . ' успешно создана');
                            } else {
                                $this->setLog("Запись уже существует");
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Получение страницы по ссылке
     *
     * @param $url
     * @return mixed|null
     */
    public function getContent($url)
    {
        $curl = $this->getCurl($url);
        $result = curl_exec($curl);
        if (!curl_errno($curl)) {
            switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                    $this->setLog("Получена страница с сайта");
                    return $result;
                    break;
                default:
                    $this->setLog("Получить страницу не удалось");
                    return null;
            }
        }
    }

    /**
     * Преобразование и подготовка даты к сохранению
     *
     * @param $date
     * @return \DateTime|string
     */
    public function prepareDate($date)
    {
        $date = trim($date);
        $stamps = explode(' ', $date);

        $day = isset($stamps[0]) ? $stamps[0] : 1;
        $month = isset($stamps[1]) ? $this->getMonth($stamps[1]) : 1;
        $year = isset($stamps[2]) ? $stamps[2] : date("Y");

        $date = new \DateTime();
        $date->setDate($year, $month, $day);
        $date->setTime(0, 0, 0);

        return $date;
    }

    /**
     * @param $month
     * @return int|null|string
     */
    public function getMonth($month)
    {
        $months = [
            1 => 'январь',
            2 => 'февраль',
            3 => 'март',
            4 => 'апрель',
            5 => 'май',
            6 => 'июнь',
            7 => 'июль',
            8 => 'август',
            9 => 'сентябрь',
            10 => 'октябрь',
            11 => 'ноябрь',
            12 => 'декабрь'
        ];

        $month = trim($month);

        foreach ($months as $key => $value) {
            $pos = mb_stripos($value, $month, 0, 'UTF-8');

            if ($pos !== false) {
                return $key;
            }
        }

        return key($months);
    }

    /**
     * @param $url
     * @return resource
     */
    public function getCurl($url)
    {
        if (!$this->_curl) {
            $this->_curl = curl_init();
            curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        }
        curl_setopt($this->_curl, CURLOPT_URL, $url);

        return $this->_curl;
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->_log;
    }

    /**
     * @param $text
     */
    public function setLog($text)
    {
        $this->_log[] = $text;
    }

    /**
     * @param $d
     */
    public function d($d)
    {
        var_dump($d);
        die();
    }
}