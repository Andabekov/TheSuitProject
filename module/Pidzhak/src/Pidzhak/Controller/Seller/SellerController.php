<?php
namespace Pidzhak\Controller\Seller;

use Pidzhak\Model\admin\Sms;
use Pidzhak\Sms\SmsUtil;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\Seller\Seller;
use Pidzhak\Form\Seller\SellerForm;

class SellerController extends AbstractActionController
{
    protected $cycleTable;
    protected $fabricTable;
    protected $smsTable;
    protected $orderTable;
    protected $authservice;


    public function indexAction()
    {
        return $this->redirect()->toRoute('order');
    }

    public function cycleslistAction()
    {
        $view = new ViewModel(array(
            'cycle' => $this->getCycleTable()->fetchAll(),
        ));
        $view->setTemplate('pidzhak/seller/cycleslist.phtml');
        return $view;
    }

    public function fabricslistAction()
    {
        $view = new ViewModel(array(
                'fabric' => $this->getFabricTable()->fetchAll(),
        ));
        $view->setTemplate('pidzhak/seller/fabricslist.phtml');
        return $view;
    }

    public function getCycleTable(){
        if (!$this->cycleTable) {
            $sm = $this->getServiceLocator();
            $this->cycleTable = $sm->get('Pidzhak\Model\admin\CycleTable');
        }
        return $this->cycleTable;
    }

    public function getFabricTable(){
        if (!$this->fabricTable) {
            $sm = $this->getServiceLocator();
            $this->fabricTable = $sm->get('Pidzhak\Model\admin\FabricTable');
        }
        return $this->fabricTable;
    }

    public function orderstocheckAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/ordersToCheck.phtml');
        return $view;
    }

    public function readyforfittingAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/ordersForFitting.phtml');
        return $view;
    }

    public function infittingAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/ordersInFitting.phtml');
        return $view;
    }

    public function intailorsAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/ordersInTailors.phtml');
        return $view;
    }

    public function happybdAction(){

        $phone = $this->params()->fromQuery('num');
        $text = $this->params()->fromQuery('text');

        if($phone!=null && $phone!='' && $text!=null && $text!=''){
            $sms = new Sms();
            $sms->number=$phone;
            $sms->text=$text;
            SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());

            return $this->redirect()->toRoute('seller', array('action' => 'happybd'));
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/happyBirthday.phtml');
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

    public function getOrderTable()
    {
        if (!$this->orderTable) {
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Pidzhak\Model\Seller\OrderTable');
        }
        return $this->orderTable;
    }

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }
}