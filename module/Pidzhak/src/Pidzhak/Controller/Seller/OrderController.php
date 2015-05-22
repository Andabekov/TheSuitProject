<?php
namespace Pidzhak\Controller\Seller;

use Pidzhak\Model\admin\Sms;
use Pidzhak\Model\Seller\OrderClothes;
use Pidzhak\Sms\SmsUtil;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\Seller\Order;
use Pidzhak\Form\Seller\OrderForm;
use Pidzhak\Form\Seller\OrderClothesForm;

class OrderController extends AbstractActionController
{

    protected $smsTable;
    protected $orderTable;
    protected $customerTable;
    protected $orderclothesTable;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
    }

    public function resetstylestoselectAction(){
        $request = $this->getRequest();
        $response   = $this->getResponse();
        $messages = array();

        if ($request->isPost()) {

            $cloth_type=$request->getPost()['cloth_type_id'];

            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $sql       = 'SELECT DISTINCT(style_num) FROM stylestable where cloth_type='.$cloth_type;
            $statement = $dbAdapter->query($sql);
            $result    = $statement->execute();

            $selectData = array();

            foreach ($result as $res) {
                array_push($selectData,$res['style_num']);
            }

            if(!empty($selectData) && $selectData!=null)
                $message = $selectData;
            else
                $message = 'empty';

            $response->setContent(\Zend\Json\Json::encode(array('success'=>1,'styles'=>$message)));

            return $response;
        }
    }

    public function addAction()
    {
        $form = new OrderForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $order = new Order();
            $form->setInputFilter($order->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $order->exchangeArray($form->getData());
                $this->getOrderTable()->saveOrder($order);

                return $this->redirect()->toRoute('order');
            } else {
                $form->highlightErrorElements();
                // other error logic
            }
        }
        return array('form' => $form);
    }


    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('order', array(
                'action' => 'add'
            ));
        }

        try {
            $order = $this->getOrderTable()->getOrder($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('order', array(
                'action' => 'index'
            ));
        }

        $form = new OrderForm();
        $form->bind($order);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($order->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getOrderTable()->saveOrder($order);

                //return $this->redirect()->toRoute('order');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('order');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->getOrderTable()->deleteOrder($id);
            }

            return $this->redirect()->toRoute('order');
        }

        return array(
            'id' => $id,
            'order' => $this->getOrderTable()->getOrder($id)
        );
    }


    public function thirdstepAction()
    {
        $order_form_id = 0;
        $order_error = "";
        $customer_id = (int)$this->params()->fromRoute('id', 0);
        $measureTypeSelect = (int)$this->params()->fromRoute('measureTypeSelect', 0);

        $orderclothesform = 0;

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new OrderForm($dbAdapter);
        $form->get('customer_id')->setValue($customer_id);
        $form->get('status')->setValue(1);

        $cform = new OrderClothesForm($dbAdapter);
        $cform->get('orderclothessubmit')->setValue('Сохранить изделие');
        $cform->get('orderclothescancel')->setValue('Отменить');
        $cform->get('typeof_measure')->setValue($measureTypeSelect);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $order = new Order();
            $form->setInputFilter($order->getInputFilter());
            $form->setData($request->getPost());

            $orderclothes = new OrderClothes();
            $cform->setInputFilter($orderclothes->getInputFilter());
            $cform->setData($request->getPost());

            $order_form_id = $request->getPost()['order_form_id'];

            $orderclothesform = 0;

            if (!empty($request->getPost()['orderclothessubmit'])) {
                $orderclothesform = 1;
                $form_valid = $form->isValid();
                $cform_valid= $cform->isValid();
                if (!$form_valid || !$cform_valid) {
                    $form->highlightErrorElements();
                    $cform->highlightErrorElements();
                } else {
                    if (!$order_form_id) {
                        $order->exchangeArray($form->getData());
                        $this->getOrderTable()->saveOrder($order);
                        $order_form_id = $this->getOrderTable()->insertedOrder();
                    }
                    if ($order_form_id) {
                        $orderclothes->exchangeArray($cform->getData());
                        $orderclothes->typeof_measure = $measureTypeSelect;
                        $orderclothes->order_id = $order_form_id;
                        $this->getOrderClothesTable()->saveOrderClothes($orderclothes);
                    }
                    $orderclothesform = 0;
                }
            }

            if (!empty($request->getPost()['addclothessubmit'])) {
                if($form->isValid()){
                    $orderclothesform = 1;
                }else{
                    $orderclothesform = 0;
                    $form->highlightErrorElements();
                }
            }

            if (!empty($request->getPost()['sendordersubmit'])) {
                if ($order_form_id) {
                    $clothes_count = $this->getOrderClothesTable()->getCountOfClothesByOrder($order_form_id);
                    if ($clothes_count <= 0){
                        $order_error = "Нельзя отправить закас с пустыми изделиями";
                    } else{
                        $order = $this->getOrderTable()->getOrder($order_form_id);
                        $this->sendSms($order);
                        return $this->redirect()->toRoute('order');
                    }
                } else {
                    $order_error = "Заполните заказ";
                }
            }

            if ($order_form_id) {
                $order = $this->getOrderTable()->getOrder($order_form_id);
                $form->bind($order);
            }
        }


        if ($customer_id == 0) {
            $customer_id = $form->get('customer_id')->getValue();
        }

        if ($customer_id != 0) {
            $customer = $this->getCustomerTable()->getCustomer($customer_id);
        }

        $view = new ViewModel(array(
                'order_form_id' => $order_form_id,
                'id' => $customer_id,
                'measureTypeSelect' => $measureTypeSelect,
                'form' => $form,
                'cform' => $cform,
                'customer' => $customer,
                'order_error' => $order_error,
                'orderclothesform' => $orderclothesform,
            )
        );
        $view->setTemplate('pidzhak/order/third.phtml');
        return $view;
    }


    /*Inversion of Control*/
    public function getOrderTable()
    {
        if (!$this->orderTable) {
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Pidzhak\Model\Seller\OrderTable');
        }
        return $this->orderTable;
    }

    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\Seller\CustomerTable');
        }
        return $this->customerTable;
    }

    /*Inversion of Control*/
    public function getOrderClothesTable()
    {
        if (!$this->orderclothesTable) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTable = $sm->get('Pidzhak\Model\Seller\OrderClothesTable');
        }
        return $this->orderclothesTable;
    }

    public function sendSms($order){

        $redactor_phones = array();
        $director_phones = array();
        $client_name = '';
        $client_phone = '';
        $order_total_price = 0;
        $seller_name = '';

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $sql       = 'SELECT firstname, lastname, mobilephone FROM customer where id='.$order->customer_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        foreach ($result as $res) {
            $client_name= $res['firstname'].' '.$res['lastname'];
            $client_phone = $res['mobilephone'];
        }

        $sql       = 'SELECT phone FROM userstable where access_type_id=4';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        foreach ($result as $res) {
            array_push($director_phones, $res['phone']);
        }

        $sql       = 'SELECT phone FROM userstable where access_type_id=2';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        foreach ($result as $res) {
            array_push($redactor_phones, $res['phone']);
        }

        $sql       = 'SELECT name, surname FROM userstable where id='.$order->seller_id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        foreach ($result as $res) {
            $seller_name = $res['name'].' '.$res['surname'];
        }

        $sql       = 'SELECT actual_amount FROM orderclothes where order_id='.$order->id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        foreach ($result as $res) {
            $order_total_price = $order_total_price + $res['actual_amount'];
        }

//        foreach($director_phones as $phone){
//            $sms = new Sms();
//            $sms->number=$phone;
//            $sms->text=' Postupil noviy zakaz ot klienta '.$client_name.' ('.$client_phone.') na summu-'.$order_total_price.'KZT. Zakaz prinyal '.$seller_name;
//
//            SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
//        }

//        foreach($director_phones as $phone){
//            $sms = new Sms();
//            $sms->number=$phone;
//            $sms->text=' Postupil noviy zakaz ot klienta '.$client_name.' ('.$client_phone.'). Zakaz prinyal '.$seller_name;
//
//            SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
//        }

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