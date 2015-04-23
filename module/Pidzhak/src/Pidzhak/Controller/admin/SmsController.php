<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 14:37
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Sms\SmsXmlParser;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SmsController extends AbstractActionController
{
    protected $smsTable;

    public function indexAction()
    {

        //SmsXmlParser::sendSmsTest();
        //SmsXmlParser::checkSmsStatusTest();

        $view = new ViewModel();
        $view->setTemplate('pidzhak/admin/sms.phtml');
        return $view;
    }

    public function getSmsTable()
    {
        if (!$this->smsTable) {
            $sm = $this->getServiceLocator();
            $this->smsTable = $sm->get('Pidzhak\Model\admin\SmsTable');
        }
        return $this->smsTable;
    }
}