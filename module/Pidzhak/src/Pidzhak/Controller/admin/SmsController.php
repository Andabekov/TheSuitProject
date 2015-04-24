<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 14:37
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\SmsForm;
use Pidzhak\Model\admin\Sms;
use Pidzhak\Sms\SmsUtil;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SmsController extends AbstractActionController
{
    protected $smsTable;

    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('pidzhak/admin/sms.phtml');
        return $view;
    }

    public function sendAction()
    {
        $form = new SmsForm();
        $form->get('submit')->setValue('Отправить');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $sms = new Sms();
            $form->setInputFilter($sms->getInputFilter());
            $form->setData($request->getPost());


            if ($form->isValid()) {
                $sms->exchangeArray($form->getData());
                SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
                return $this->redirect()->toRoute('sms');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/sendSms.phtml');
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