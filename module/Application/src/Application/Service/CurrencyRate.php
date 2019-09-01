<?php

namespace Application\Service;

use Zend\Soap\Client;
use Zend\ServiceManager\ServiceManager;

/**
 * @author Kostya Bulaev <viphedin@gmail.com>
 */
class CurrencyRate
{
    protected $cache = [];

    /**
     * @var ServiceManager
     */
    protected $sm = null;

    /**
     * @var string
     */
    protected $wsdl = '';

    public function __construct(ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;

        $config = $this->sm->get('Config');

        $this->wsdl = $config['currencyWSDL'] ?? '';
    }

    /**
     * получение курса валюты для заданного кода и даты
     * @param string $date {format: Y-m-d}
     * @param string $code
     * @return array|null ['code', 'amount', 'rate']
     */
    public function getRate($date, $code)
    {
        if ($currecies = $this->load($date)) {
            $currency = $this->find($code, $currecies);

            if ($currency) {
                return [
                    'code' => $currency['VchCode'],
                    'amount' => $currency['Vnom'],
                    'rate' => $currency['Vcurs']
                ];
            }
        }

        return null;
    }

    /**
     * загрузка данных с www.cbr.ru, если данные в кеше, то возращает его\
     * @param string $date {format: Y-m-d}
     * @return array|null
     */
    protected function load($date)
    {
        if (!isset($this->cache[$date])) {
            $currecies = [];

            $client = new Client($this->wsdl);

            $params = new \stdClass();
            $params->On_date = $date;

            $data = $client->GetCursOnDate($params);

            if (isset($data->GetCursOnDateResult->any)) {
                $xml = simplexml_load_string($data->GetCursOnDateResult->any);
                foreach ($xml->ValuteData->ValuteCursOnDate as $row) {
                    $currecies[] = (array)$row;
                }
            }

            $this->cache[$date] = $currecies;
        }

        return $this->cache[$date];
    }

    /**
     * поиск валюты по коду
     * @param string $code
     * @param array $data
     */
    protected function find($code, $data)
    {
        foreach ($data as $row) {
            if ($row['VchCode'] == $code) {
                return $row;
            }
        }

        return null;
    }
}
