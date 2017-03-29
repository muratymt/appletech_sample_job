<?php

namespace APPLE\controller;

use simplehtmldom_1_5\simple_html_dom_node;
use Sunra\PhpSimple\HtmlDomParser;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.03.2017
 * Time: 10:15
 */
class ConsoleController extends Controller
{
    const ALFA_URL = 'http://alfa.kz/pc/notebooks/all/';

    public function actionBoy($position)
    {
        if (!is_numeric($position) || !preg_match('/^\d+$/', $position)) {
            echo 'Position value need in integer format';
            return;
        }
        $position = (int)$position;
        if ($position === 0){
            echo 0, "\n";
            return;
        }
        if ($position <= 5) {
            echo 1, "\n";
            return;
        }

        $steps = intdiv($position, 5);
        if ($position % 5 !== 0) {
            $steps++;
        }
        echo $steps, "\n";
    }

    public function actionAlfa($brand)
    {
        list($status, $content) = $this->getPage(self::ALFA_URL . $brand);
        if ((int)$status === 301 || (int)$status === 302) {
            echo 'Product not found';
            return;
        }

        if ((int)$status !== 200) {
            echo 'Error request';
            return;
        }

        $result = $this->parse($content);
        if ($result === false) {
            echo 'Not found price on page';
            return;
        }

        list($min, $max) = $result;
        echo 'Min price: ', $min, "\n";
        echo 'Max price: ', $max, "\n";
    }

    private function parse($content)
    {
        $min = null;
        $max = null;
        $doc = HtmlDomParser::str_get_html($content);
        $prices = $doc->find('div.messages-list div.price-container span.__price span.num');
        if ($prices === null) {
            return false;
        }
        /** @var $prices simple_html_dom_node[] */
        if (is_array($prices)) {
            foreach ($prices as $price) {
                $z = $this->normalizePrice((string)$price);
                if ($min === null || $z < $min) {
                    $min = $z;
                }
                if ($max === null || $z > $max) {
                    $max = $z;
                }
            }
            return [$min, $max];
        }

        if (is_a($prices, simple_html_dom_node::class)) {
            $price = $this->normalizePrice((string)$prices);
            return [$price, $price];
        }
    }

    private function normalizePrice($price)
    {
        return str_replace(' ', '', strip_tags($price));
    }

    private function getPage($url)
    {
        $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
        curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
        curl_setopt($ch, CURLOPT_ENCODING, '');        // обрабатывает все кодировки
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0);       // останавливаться после 10-ого редиректа

        $content = curl_exec($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        return [$header['http_code'], $content];
    }
}