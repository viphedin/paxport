<?php

namespace Application\Command;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author Kostya Bulaev <viphedin@gmail.com>
 * загрузка курса валют за текущую дату и их сохранение в БД
 */
class CurrencyController extends AbstractActionController
{
    const CURRENCIES = [
        'USD', 'EUR'
    ];

    public function dailyAction()
    {
        $service = $this->getServiceLocator()->get('Service\CurrencyRate');

        $storage = $this->getServiceLocator()->get('Storage\CurrencyRate');

        $date = date('Y-m-d', time());

        foreach (self::CURRENCIES as $code) {
            $entry = $service->getRate($date, $code);

            if ($entry) {
                $storage->saveRate($date, $entry);
            }
        }
    }
}
