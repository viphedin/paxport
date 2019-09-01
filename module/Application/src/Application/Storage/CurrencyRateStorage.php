<?php

namespace Application\Storage;

use Zend\Db\Adapter\Adapter;

/**
 * @author Kostya Bulaev <viphedin@gmail.com>
 */
class CurrencyRateStorage
{
    /**
     * @var Adapter
     */
    protected $adapter = null;

    protected $table = 'currency_rate';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * полуcение списка валют для вывода
     */
    public function fetchAll()
    {
        $statement = $this->adapter->query('SELECT * FROM ' . $this->table . ' ORDER BY date DESC, code ASC');

        return $statement->execute();
    }

    /**
     * сохранение курса валюты в БД
     * @param string $date
     * @param array $entry ['code', 'amount', 'rate']
     */
    public function saveRate($date, $entry)
    {
        $sql = 'INSERT IGNORE INTO ' . $this->table . '(date, code, amount, rate) VALUES (?, ?, ?, ?)';

        $statement = $this->adapter->query($sql);

        $parameters = [
            $date, $entry['code'], $entry['amount'], $entry['rate']
        ];
        
        $statement->execute($parameters);
    } 
}