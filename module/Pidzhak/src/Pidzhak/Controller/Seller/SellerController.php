<?php
namespace Pidzhak\Controller\Seller;

use Pidzhak\Form\Redactor\BodyMeasureForm;
use Pidzhak\Form\Redactor\ClothMeasureForm;
use Pidzhak\Form\Redactor\OrderClothesEnForm;
use Pidzhak\Form\redactor\TestModelForm;
use Pidzhak\Form\Seller\OrderClothesForm;
use Pidzhak\Model\admin\Sms;
use Pidzhak\Model\Seller\OrderClothes;
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
    protected $orderclothesTable;
    protected $systemcodeTable;
    protected $orderclothesTableEn;
    protected $bodyMeasureTable;
    protected $clotherMeasureTable;


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

        $id = (int)$this->params()->fromRoute('id', 0);

        if($id){

            try {
                $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
//                $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
                $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
            } catch (\Exception $ex) {
                return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
            }

            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $form = new OrderClothesForm($dbAdapter);
            $form->bind($orderclothes);

            $en_form = new OrderClothesEnForm($dbAdapter);
//            $en_form->bind($orderclothesEN);

            $measurement_type = $orderclothes->typeof_measure;
            $cloth_type = $orderclothes->product_id;
            $order_id = $orderclothes->order_id;

            $bm_form = new BodyMeasureForm($dbAdapter);
            $cm_form = new ClothMeasureForm($dbAdapter);

            if($measurement_type==1){
                $measurements = $this->getBodyMeasureTable()->getMeasure($cloth_type, $order_id);
                $bm_form->bind($measurements);
            } else {
                $measurements = $this->getClotherMeasureTable()->getMeasure($cloth_type, $order_id);
                $cm_form->bind($measurements);
            }

            $arrayMy = array();
            foreach($systemCodeList as $temp){
                array_push($arrayMy,
                    array(
                        'code'            => $temp->code,
                        'fabric_optional' => $temp->fabric_optional,
                        'description'     => $temp->description,
                    ));
            }

            $sc_form = new TestModelForm();
            $sc_form->populateValues(array("systemcode"=>$arrayMy));

            $view = new ViewModel(array(
                'form' => $form,
                'en_form' => $en_form,
                'bm_form' => $bm_form,
                'cm_form' => $cm_form,
                'measurement_type' => $measurement_type,
                'sc_form' => $sc_form,
            ));
            $view->setTemplate('pidzhak/seller/orderCheck.phtml');
            return $view;
        }

        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/ordersToCheck.phtml');
        return $view;
    }

    public function orderdenychangeAction(){

        $id = (int)$this->params()->fromRoute('id', 0);
        $request = $this->getRequest();

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new OrderClothesForm($dbAdapter);
        $en_form = new OrderClothesEnForm($dbAdapter);
        $bm_form = new BodyMeasureForm($dbAdapter);
        $cm_form = new ClothMeasureForm($dbAdapter);

        if(!$id) {
            $view = new ViewModel(array(
                'orders' => $this->getOrderTable()->fetchAll(),
                'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
            ));
            $view->setTemplate('pidzhak/seller/ordersToCheck.phtml');
            return $view;
        }

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
//                $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
        }
        
        $form->bind($orderclothes);
        $form->get('orderclothessubmit')->setValue('Откатить назад с исправлениями');

        //            $en_form->bind($orderclothesEN);

        $measurement_type = $orderclothes->typeof_measure;
        $cloth_type = $orderclothes->product_id;
        $order_id = $orderclothes->order_id;

        if($measurement_type==1){
            $measurements = $this->getBodyMeasureTable()->getMeasure($cloth_type, $order_id);
            $bm_form->bind($measurements);
        } else {
            $measurements = $this->getClotherMeasureTable()->getMeasure($cloth_type, $order_id);
            $cm_form->bind($measurements);
        }

        $arrayMy = array();
        foreach($systemCodeList as $temp){
            array_push($arrayMy,
                array(
                    'code'            => $temp->code,
                    'fabric_optional' => $temp->fabric_optional,
                    'description'     => $temp->description,
                ));
        }

        $sc_form = new TestModelForm();
        $sc_form->populateValues(array("systemcode"=>$arrayMy));

        if ($request->isPost()) {

            $form->setData($request->getPost());

            var_dump($form->isValid());

            var_dump($form->getMessages());
//
//            if ($form->isValid()) {
//                $this->getOrderClothesTable()->saveOrderClothes($orderclothes);
//
//                return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
//            } else{
//                var_dump($form->getMessages());
//            }

        }

        $view = new ViewModel(array(
            'form' => $form,
            'en_form' => $en_form,
            'bm_form' => $bm_form,
            'cm_form' => $cm_form,
            'measurement_type' => $measurement_type,
            'sc_form' => $sc_form,
        ));
        $view->setTemplate('pidzhak/seller/denyWithChange.phtml');
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

    public function sendsmsAction(){
        $phone = $this->params()->fromQuery('num');
        $text = $this->params()->fromQuery('text');

        if($phone!=null && $phone!='' && $text!=null && $text!=''){
            $sms = new Sms();
            $sms->number=$phone;
            $sms->text=$text;
            SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
        }

        $url = $this->params()->fromQuery('url');
        if($url=='inoffice')
            return $this->redirect()->toRoute('seller', array('action' => 'readyforfitting'));
        else
            return $this->redirect()->toRoute('seller', array('action' => 'intailors'));
    }

    public function orderrollbackAction(){

        $id = $this->params()->fromQuery('id');
        $comment = $this->params()->fromQuery('comment');

        if($id!=null && $id!='' && $comment!=null && $comment!=''){
            $this->getOrderClothesTable()->rollBackOrder($id, $comment);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
    }

    public function orderconfirmAction(){

        $id = $this->params()->fromRoute('id');
        if($id!=null && $id!=''){
            $this->getOrderClothesTable()->confirmOrder($id);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
    }

    public function setfittingdateAction(){
        $id = $this->params()->fromQuery('id');
        $date = $this->params()->fromQuery('date');

        if($date!=null && $date!='' && $id!=null && $id!=''){
            $this->getOrderClothesTable()->setFittingDate($date, $id);
        }

        $url = $this->params()->fromQuery('url');
        if($url=='inoffice')
            return $this->redirect()->toRoute('seller', array('action' => 'readyforfitting'));
        else
            return $this->redirect()->toRoute('seller', array('action' => 'intailors'));
    }

    public function givetotailorAction(){

        $id = $this->params()->fromQuery('id');
        $tailor_id = $this->params()->fromQuery('tailor_id');

        if($tailor_id!=null && $tailor_id!='' && $id!=null && $id!=''){
            $this->getOrderClothesTable()->giveToTailor($id, $tailor_id);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'infitting'));
    }

    public function givetoclientAction(){
        $id = $this->params()->fromQuery('id');

        if($id!=null && $id!=''){
            $this->getOrderClothesTable()->giveToClient($id);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'infitting'));
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

    public function getOrderClothesTable()
    {
        if (!$this->orderclothesTable) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTable = $sm->get('Pidzhak\Model\Seller\OrderClothesTable');
        }
        return $this->orderclothesTable;
    }

    public function getOrderClothesTableEn()
    {
        if (!$this->orderclothesTableEn) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTableEn = $sm->get('Pidzhak\Model\Seller\OrderClothesTableEn');
        }
        return $this->orderclothesTableEn;
    }

    public function getSystemCodesTable(){
        if (!$this->systemcodeTable) {
            $sm = $this->getServiceLocator();
            $this->systemcodeTable = $sm->get('Pidzhak\Model\redactor\SystemCodeTable');
        }
        return $this->systemcodeTable;
    }

    public function getBodyMeasureTable()
    {
        if (!$this->bodyMeasureTable) {
            $sm = $this->getServiceLocator();
            $this->bodyMeasureTable = $sm->get('Pidzhak\Model\Seller\BodyMeasureTable');
        }
        return $this->bodyMeasureTable;
    }

    public function getClotherMeasureTable()
    {
        if (!$this->clotherMeasureTable) {
            $sm = $this->getServiceLocator();
            $this->clotherMeasureTable = $sm->get('Pidzhak\Model\Seller\ClotherMeasureTable');
        }
        return $this->clotherMeasureTable;
    }

}