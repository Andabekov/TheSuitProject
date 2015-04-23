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
use Pidzhak\Sms\SmsXmlParser;
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
                $this->getSmsTable()->saveSms($sms);
                $lastInsertedSms = $this->getSmsTable()->insertedSms();

                $saved_sms = $this->getSmsTable()->getSms($lastInsertedSms);

                if($saved_sms==null)
                    return $this->redirect()->toRoute('sms');


                $numbers_array = SmsXmlParser::add_numbers_to_send_sms($sms->number, $lastInsertedSms);

                $send_xml = SmsXmlParser::buildSendSms($saved_sms->text, $numbers_array);

                $response_xml = SmsXmlParser::sendSmsToUrl($send_xml);

                $RESPONSE = SmsXmlParser::parseResponseXml($response_xml);

                if($RESPONSE==null)
                    return $this->redirect()->toRoute('sms');

                $saved_sms->first_status = $RESPONSE->status;
                $saved_sms->credits = $RESPONSE->credits;

                $this->getSmsTable()->saveSms($saved_sms);

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