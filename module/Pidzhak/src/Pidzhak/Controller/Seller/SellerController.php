<?php
namespace Pidzhak\Controller\seller;

use Pidzhak\Form\accountant\CertificateForm;
use Pidzhak\Form\redactor\BodyMeasureForm;
use Pidzhak\Form\redactor\ClothMeasureForm;
use Pidzhak\Form\redactor\OrderClothesEnForm;
use Pidzhak\Form\redactor\TestModelForm;
use Pidzhak\Form\seller\CustomerForm;
use Pidzhak\Form\seller\FinanceOperationsForm;
use Pidzhak\Form\seller\MeasureForm;
use Pidzhak\Form\seller\OrderClothesForm;
use Pidzhak\Form\seller\PhoneCallForm;
use Pidzhak\GoogleContact\GoogleContactXmlParser;
use Pidzhak\Model\accountant\Certificate;
use Pidzhak\Model\admin\Sms;
use Pidzhak\Model\seller\BodyMeasure;
use Pidzhak\Model\seller\ClotherMeasure;
use Pidzhak\Model\seller\Customer;
use Pidzhak\Model\seller\FinanceOperations;
use Pidzhak\Model\seller\PhoneCall;
use Pidzhak\Sms\SmsUtil;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
    protected $financeOperationsTable;
    protected $certificateTable;
    protected $customerTable;
    protected $phoneCallTable;
    protected $taskTable;

    public function emailAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/requests.phtml');
        return $view;
    }

    public function addrequestAction(){
        $request_type = $this->params()->fromPost('request_type');
        $request_body = $this->params()->fromPost('request_body');

        if($request_body!='' && $request_type!=''){
            $this->getOrderClothesTable()->addRequest($request_type, $request_body);
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/addRequest.phtml');
        return $view;
    }

    public function callsmsAction(){
        $phone = $this->params()->fromPost('phone');
        $text = $this->params()->fromPost('text');

        if($phone!='' && $text!=''){
            $sms = new Sms();
            $sms->number=$phone;
            $sms->text=$text;
            SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/myday.phtml');
        return $view;
    }

    public function editclientAction(){

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'clients'));
        }

        try {
            $customer = $this->getCustomerTable()->getCustomer($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'clients'));
        }

        $form  = new CustomerForm();
        $form->bind($customer);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCustomerTable()->saveCustomer($customer);
                return $this->redirect()->toRoute('seller', array('action' => 'clients'));
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'back' => '/seller/clients',
            )
        );
        $view->setTemplate('pidzhak/seller/editClient.phtml');
        return $view;
    }

    public function clientsAction(){

        $view = new ViewModel(array());
        $view->setTemplate('pidzhak/seller/clients.phtml');
        return $view;

    }

    public function setstatus15Action(){
        $id = $this->params()->fromPost('id');
        if($id!=''){
            $this->getOrderClothesTable()->setStatus15($id);
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/myday.phtml');
        return $view;
    }

    public function setstatus7Action(){
        $id = $this->params()->fromPost('id');
        if($id!=''){
            $this->getOrderClothesTable()->setStatus7($id);
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/myday.phtml');
        return $view;
    }

    public function mydayAction(){
        $from_date = $this->params()->fromQuery('from_date');
        $to_date   = $this->params()->fromQuery('to_date');

        $fittings = $this->getOrderClothesTable()->getFittings($from_date, $to_date);
        $appoints = $this->getOrderClothesTable()->getAppoints($from_date, $to_date);
        $reminds = $this->getOrderClothesTable()->getReminds($from_date, $to_date);
        $bdays = $this->getOrderClothesTable()->getBdays($from_date, $to_date);
        $deadlines = $this->getOrderClothesTable()->getDeadlines($from_date, $to_date);
        $startcycles = $this->getOrderClothesTable()->getStartcycles($from_date, $to_date);
        $endcycles = $this->getOrderClothesTable()->getEndcycles($from_date, $to_date);
        $orderclothes = $this->getOrderClothesTable()->getClothes($from_date, $to_date);
        $certs = $this->getOrderClothesTable()->getCerts($from_date, $to_date);
        $givenclothes = $this->getOrderClothesTable()->getGivenclothes($from_date, $to_date);
        $calls = $this->getOrderClothesTable()->getCalls($from_date, $to_date);

        $view = new ViewModel(array(
            'from_date' => $from_date,
            'to_date' => $to_date,
            'fittings' => $fittings,
            'appoints' => $appoints,
            'reminds' => $reminds,
            'bdays' => $bdays,
            'deadlines' => $deadlines,
            'startcycles' => $startcycles,
            'endcycles' => $endcycles,
            'orderclothes' => $orderclothes,
            'certs' => $certs,
            'givenclothes' => $givenclothes,
            'calls' => $calls,

        ));
        $view->setTemplate('pidzhak/seller/myday.phtml');
        return $view;
    }

    public function createcall2Action(){

        $customer_id = (int)$this->params()->fromRoute('id');
        $customer = $this->getCustomerTable()->getCustomer($customer_id);

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new PhoneCallForm($dbAdapter);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $phoneCall = new PhoneCall();
            $form->setInputFilter($phoneCall->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $phoneCall->exchangeArray($form->getData());
                $this->getPhoneCallTable()->savePhoneCall($phoneCall);

                return $this->redirect()->toRoute('seller', array('action' => 'calls'));
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
                'customer'=>$customer,
                'form' => $form,
                'id' => $customer_id
            )
        );
        $view->setTemplate('pidzhak/seller/createCall2.phtml');
        return $view;
    }

    public function createcallAction(){

        $form  = new CustomerForm();
        $form->get('submit')->setValue('Сохранить и продолжить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $customer = new Customer();
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            $idval = $request->getPost()->get('id');
            if($idval){
                return $this->redirect()->toRoute('seller'
                    , array(
                        'action' => 'createcall2',
                        'id' => $idval
                    ));
            }elseif ($form->isValid()) {

                $full_name=$request->getPost()->firstname.' '.$request->getPost()->lastname.' '.$request->getPost()->middlename;
                $mobile_phone=$request->getPost()->mobilephone;
                $access_token=$request->getPost()->access_token;

                try {
                    GoogleContactXmlParser::createContactFinal($full_name,$mobile_phone,$access_token);
                }
                catch (\Exception $ex) {
                }

                $customer->exchangeArray($form->getData());
                $this->getCustomerTable()->saveCustomer($customer);
                $idval = $this->getCustomerTable()->insertedCustomer();

                return $this->redirect()->toRoute('seller'
                    , array(
                        'action' => 'createcall2',
                        'id' => $idval
                    ));
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
                'form' => $form,
                'back' => '#',
            )
        );
        $view->setTemplate('pidzhak/seller/createCall1.phtml');
        return $view;
    }

    public function callsAction(){

        $from_date = $this->params()->fromQuery('from_date');
        $to_date   = $this->params()->fromQuery('to_date');
        $seller_id = (int) $this->params()->fromQuery('seller_id');

        $calls = $this->getPhoneCallTable()->getCallsByDateAndSeller($from_date, $to_date, $seller_id);
        $sellers = $this->getOrderClothesTable()->getSellers();

        $view = new ViewModel(array(
            'sellers'=> $sellers,
            'calls' => $calls,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'seller_id' => $seller_id,
        ));
        $view->setTemplate('pidzhak/seller/callsList.phtml');
        return $view;
    }

    public function profitAction(){

        $from_date = $this->params()->fromQuery('from_date');
        $to_date   = $this->params()->fromQuery('to_date');
        $seller_id = (int) $this->params()->fromQuery('seller_id');

        $orderclothes = $this->getOrderClothesTable()->getByDateAndSeller($from_date, $to_date, $seller_id);

        $sellers = $this->getOrderClothesTable()->getSellers();

        $view = new ViewModel(array(
            'sellers'=> $sellers,
            'orderclothes' => $orderclothes,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'seller_id' => $seller_id,
        ));
        $view->setTemplate('pidzhak/seller/profit.phtml');
        return $view;
    }

    public function certAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/certificates.phtml');
        return $view;
    }

    public function addcertAction(){

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new CertificateForm($dbAdapter);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $certificate = new Certificate();
            $form->setInputFilter($certificate->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $certificate->exchangeArray($form->getData());

                $directors = $this->getOrderClothesTable()->getDirectorNums();

                foreach($directors as $director){
                    $sms = new Sms();
                    $sms->number=$director['phone'];

                    $sms->text='Купили подарочный сертификат на сумму: '.$certificate->cost.' тенге';
                    SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
                }

                $this->getCertificateTable()->saveCertificate($certificate);

                return $this->redirect()->toRoute('seller', array('action' => 'cert'));
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('pidzhak/seller/addCertificate.phtml');
        return $view;
    }

    public function addfinanceAction(){

        $form = new FinanceOperationsForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $finance = new FinanceOperations();
            $form->setInputFilter($finance->getInputFilter());
            $form->setData($request->getPost());

            if($request->getPost()['oper_type']=='Штрафы/Бонусы'){
                $oper = new FinanceOperations();
                $oper->oper_type=$request->getPost()['oper_type'];
                $oper->oper_cost=0;
                $oper->oper_date=$request->getPost()['oper_date'];
                $oper->oper_status=0;
                $oper->oper_comment='Штраф <br>Тип штрафа: '.$request->getPost()['penalty_type'].'<br>Продавец: '.$request->getPost()['seller_id']."<br>Комментарии: ".$request->getPost()['penalty_name'];


                $this->getFinanceOperationsTable()->saveFinanceOperations($oper);

                return $this->redirect()->toRoute('seller', array('action' => 'finance'));
            } else{
                if ($form->isValid()) {
                    $finance->exchangeArray($form->getData());
                    $this->getFinanceOperationsTable()->saveFinanceOperations($finance);

                    return $this->redirect()->toRoute('seller', array('action' => 'finance'));
                }else {
                    $form->highlightErrorElements();
                }
            }
        }

        $sellers = $this->getOrderClothesTable()->getSellersList();
        $penalties = $this->getOrderClothesTable()->getPenaltiesList();

        $view = new ViewModel(array(
            'form' => $form,
            'sellers' => $sellers,
            'penalties' => $penalties,
        ));
        $view->setTemplate('pidzhak/seller/addFinanceOper.phtml');
        return $view;
    }

    public function financeAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/financeOperations.phtml');
        return $view;
    }

    public function givenclothAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/noFeedbackClothes.phtml');
        return $view;
    }

    public function feedbackAction(){
        $id = (int)$this->params()->fromPost('id');
        $comment = $this->params()->fromPost('comment');

        if($id && $comment) {
            $this->getOrderClothesTable()->feedback($id, $comment);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'givencloth'));
    }

    public function indexAction()
    {
        return $this->redirect()->toRoute('seller/myday');
    }

    public function comingordersAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/comingOrders.phtml');
        return $view;
    }

    public function setarrivedAction(){
        $id = (int)$this->params()->fromPost('id');
        $comment = $this->params()->fromPost('comment');

        if($id && $comment) {
            $this->getOrderClothesTable()->setArrived($id, $comment);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'comingorders'));
    }

    public function insertmeasureAction(){

        $id = (int)$this->params()->fromRoute('id', 0);
        $request = $this->getRequest();

        if(!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'fillmeasure'));
        }

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothesByOrderId($id);
            $clientId = $this->getOrderTable()->getClientIdByOrderId($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'fillmeasure'));
        }

        $form  = new MeasureForm();
        $form->get('submit')->setAttribute('value', 'Сохранить');
        $form->get('customer_id_1') ->setValue((int)$clientId);
        $form->get('customer_id_2') ->setValue((int)$clientId);
        $form->get('customer_id_3') ->setValue((int)$clientId);
        $form->get('customer_id_4') ->setValue((int)$clientId);
        $form->get('customer_id_5') ->setValue((int)$clientId);
        $form->get('c_customer_id_1') ->setValue((int)$clientId);
        $form->get('c_customer_id_2') ->setValue((int)$clientId);
        $form->get('c_customer_id_3') ->setValue((int)$clientId);

        foreach($orderclothes as $ordercloth){
            if($ordercloth['typeof_measure']==1)
                switch($ordercloth['product_id']){
                    case 1: $form->get('order_cloth_id_1') ->setValue($ordercloth['id']); break;
                    case 2: $form->get('order_cloth_id_2') ->setValue($ordercloth['id']); break;
                    case 4: $form->get('order_cloth_id_3') ->setValue($ordercloth['id']); break;
                    case 3: $form->get('order_cloth_id_4') ->setValue($ordercloth['id']); break;
                    case 5: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;

                    case 6: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 7: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 8: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 9: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 10: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                }
            else
                switch($ordercloth['product_id']){
                    case 1: $form->get('c_order_cloth_id_1') ->setValue($ordercloth['id']); break;
                    case 2: $form->get('c_order_cloth_id_2') ->setValue($ordercloth['id']); break;
                    case 4: $form->get('c_order_cloth_id_3') ->setValue($ordercloth['id']); break;

                    case 6: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 7: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 8: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 9: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                    case 10: $form->get('order_cloth_id_5') ->setValue($ordercloth['id']); break;
                }
        }

        if ($request->isPost()) {
            $clothermeasure = new ClotherMeasure();
            $bodymeasure = new BodyMeasure();
            $form_edited = new MeasureForm();
            $form_edited->setData($request->getPost());

            if ($form_edited->isValid()) {

                foreach($orderclothes as $ordercloth){
                    if($ordercloth['typeof_measure']==1)
                        switch($ordercloth['product_id']){
                            case 1:
                                $bodymeasure->setPostfix('_1');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 2:
                                $bodymeasure->setPostfix('_2');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 4:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->clother_id=4;
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
//                                var_dump($bodymeasure);
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 3:
                                $bodymeasure->setPostfix('_4');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->clother_id=3;
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 5:
                                $bodymeasure->setPostfix('_5');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 6:
                                $bodymeasure->setPostfix('_2');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 7:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 8:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 9:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 10:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                        }
                    else
                        switch($ordercloth['product_id']){
                            case 1:
                                $clothermeasure->setPostfix('_1');
                                $clothermeasure->setPrefix('c_');
                                $clothermeasure->exchangeArray($form_edited->getData());
                                $clothermeasure->order_cloth_id = $ordercloth['id'];
                                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);
                                break;
                            case 2:
                                $clothermeasure->setPostfix('_2');
                                $clothermeasure->setPrefix('c_');
                                $clothermeasure->exchangeArray($form_edited->getData());
                                $clothermeasure->order_cloth_id = $ordercloth['id'];
                                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);
                                break;
                            case 4:
                                $clothermeasure->setPostfix('_3');
                                $clothermeasure->setPrefix('c_');
                                $clothermeasure->exchangeArray($form_edited->getData());
                                $clothermeasure->order_cloth_id = $ordercloth['id'];
                                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);
                                break;
                            case 6:
                                $bodymeasure->setPostfix('_2');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 7:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 8:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 9:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                            case 10:
                                $bodymeasure->setPostfix('_3');
                                $bodymeasure->exchangeArray($form_edited->getData());
                                $bodymeasure->order_cloth_id = $ordercloth['id'];
                                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);
                                break;
                        }
                }
                $this->getOrderClothesTable()->setStatus10($id);

                return $this->redirect()->toRoute('seller', array('action' => 'fillmeasure'));
            }
        }

        $clientName = $this->getOrderClothesTable()->getClientName2($id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        $view = new ViewModel(array(
            'id'=>$id,
            'form'=>$form,
            'clientName'=>$clientName,
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username'],
            'clientId' => (int)$clientId
        ));
        $view->setTemplate('pidzhak/seller/insertMeasurements.phtml');
        return $view;
    }

    public function fillmeasureAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/fillMeasurements.phtml');
        return $view;
    }

    public function sendredAction(){
        $id = (int)$this->params()->fromPost('id');
        $cycle_id = (int)$this->params()->fromPost('cycle_id');

        if(!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        if($cycle_id==-10 || $cycle_id==-14){
            $redactors = $this->getOrderClothesTable()->getRedactorNums();

            foreach($redactors as $redactor){
                $sms = new Sms();
                $sms->number=$redactor['phone'];

                $sms->text='Поступил срочный заказ!';
                SmsUtil::sendSmsWithDbWrite($sms, $this->getSmsTable());
            }
        }

        $this->getOrderClothesTable()->sendClothToRedactor($id);

        return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
    }

    public function changeclothAction(){
        $id = (int)$this->params()->fromRoute('id', 0);

        if(!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
        }

        $this->getOrderClothesTable()->changeCodeStatus($id, 111);
        $this->getOrderClothesTable()->changeCloth($id);

        return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
    }

    public function changeorderAction(){
        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/seller/changeOrder.phtml');
        return $view;
    }

    public function successAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/successGoBack.phtml');
        return $view;
    }

    public function changegenAction(){

        $id = (int)$this->params()->fromRoute('id', 0);
        $request = $this->getRequest();

        if(!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new OrderClothesForm($dbAdapter);
        $form->bind($orderclothes);
        $form->get('orderclothessubmit')->setValue('Сохранить');

        $clientName = $this->getOrderClothesTable()->getClientName2($orderclothes->order_id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getOrderClothesTable()->saveOrderClothes($orderclothes);
                return $this->redirect()->toRoute('seller', array('action' => 'success'));
            } else{
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
            'id' => $id,
            'form' => $form,
            'clientName'=>$clientName
        ));
        $view->setTemplate('pidzhak/seller/changeGeneral.phtml');
        return $view;
    }

    public function watcholdcodesAction(){
        $id = (int) $this->params()->fromRoute('id', 0);


        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
            $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'index'));
//            var_dump($ex);
        }
//
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $en_form = new OrderClothesEnForm($dbAdapter);
        $en_form->bind($orderclothesEN);

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

        $measurement_type = $orderclothesEN->measurement_type;
        $order_cloth_id = $orderclothesEN->order_cloth_id;

        $bm_form = new BodyMeasureForm($dbAdapter);
        $cm_form = new ClothMeasureForm($dbAdapter);
        if($measurement_type==1){
            $measurements = $this->getBodyMeasureTable()->getMeasureByOrderClothId($order_cloth_id);
            $bm_form->bind($measurements);
        } else {
            $measurements = $this->getClotherMeasureTable()->getMeasureByOrderClothId($order_cloth_id);
            $cm_form->bind($measurements);
        }

        $clientName = $this->getOrderClothesTable()->getClientName2($orderclothes->order_id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        $view = new ViewModel(array(
                'en_form' => $en_form,
                'sc_form' => $sc_form,
                'bm_form' => $bm_form,
                'cm_form' => $cm_form,
                'measurement_type' => $measurement_type,
                'clientName' => $clientName,
            )
        );
        $view->setTemplate('pidzhak/seller/watchOldCodes.phtml');
        return $view;
    }

    public function watcholdAction(){
        $id = (int)$this->params()->fromRoute('id', 0);
        $request = $this->getRequest();

        if(!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $measurement_type = $orderclothes->typeof_measure;
        $cloth_type = $orderclothes->product_id;

        $bm_form = new BodyMeasureForm($dbAdapter);
        $cm_form = new ClothMeasureForm($dbAdapter);

        if($measurement_type==1){
            $measurements = $this->getBodyMeasureTable()->getMeasureByOrderClothId($id);
            $bm_form->bind($measurements);
        }
        else {
            $measurements = $this->getClotherMeasureTable()->getMeasureByOrderClothId($id);
            $cm_form->bind($measurements);
        }

        if ($request->isPost()) {
            $bm_form->setData($request->getPost());
            $bm_form->setInputFilter($measurements->getInputFilter());
            $cm_form->setData($request->getPost());

            if ($measurement_type==1 && $bm_form->isValid()) {
                $this->getBodyMeasureTable()->saveBodyMeasure($measurements);
                return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
            }
            if ($measurement_type==2 && $cm_form->isValid()) {
                $this->getClotherMeasureTable()->saveClotherMeasure($measurements);
                return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
            }
        }

        $clientName = $this->getOrderClothesTable()->getClientName2($orderclothes->order_id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        $view = new ViewModel(array(
            'id' => $id,
            'measurement_type' => $measurement_type ,
            'bm_form' => $bm_form,
            'cm_form' => $cm_form,
            'clientName' => $clientName,
            'cloth_type' => (int) $cloth_type,
        ));
        $view->setTemplate('pidzhak/seller/watchOldMeasurements.phtml');
        return $view;
    }

    public function changemeasAction(){
        $id = (int)$this->params()->fromRoute('id', 0);
        $request = $this->getRequest();

        if(!$id) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array('action' => 'changeorder'));
        }

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $measurement_type = $orderclothes->typeof_measure;
        $cloth_type = $orderclothes->product_id;

        $bm_form = new BodyMeasureForm($dbAdapter);
        $cm_form = new ClothMeasureForm($dbAdapter);

        if($measurement_type==1){
            $measurements = $this->getBodyMeasureTable()->getMeasureByOrderClothId($id);
            $bm_form->bind($measurements);
        }
        else {
            $measurements = $this->getClotherMeasureTable()->getMeasureByOrderClothId($id);
            $cm_form->bind($measurements);
        }

        if ($request->isPost()) {
            $bm_form->setData($request->getPost());
            $bm_form->setInputFilter($measurements->getInputFilter());
            $cm_form->setData($request->getPost());

            if ($measurement_type==1 && $bm_form->isValid()) {
                $this->getBodyMeasureTable()->saveBodyMeasure($measurements);
                return $this->redirect()->toRoute('seller', array('action' => 'success'));
            } else if ($measurement_type==2 && $cm_form->isValid()) {
                $this->getClotherMeasureTable()->saveClotherMeasure($measurements);
                return $this->redirect()->toRoute('seller', array('action' => 'success'));
            } else {
                var_dump($bm_form->getMessages());
                var_dump($cm_form->getMessages());
            }
        }

        $clientName = $this->getOrderClothesTable()->getClientName2($orderclothes->order_id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        $view = new ViewModel(array(
            'id' => $id,
            'measurement_type' => $measurement_type ,
            'bm_form' => $bm_form,
            'cm_form' => $cm_form,
            'clientName' => $clientName,
            'cloth_type' => (int) $cloth_type,
        ));
        $view->setTemplate('pidzhak/seller/changeMeasurements.phtml');
        return $view;
    }

    public function cycleslistAction()
    {
        $view = new ViewModel(array(
            'cycle' => $this->getCycleTable()->fetchAll(),
        ));
        $view->setTemplate('pidzhak/seller/cyclesList.phtml');
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
                $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
                $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
            } catch (\Exception $ex) {
                return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
            }

            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $form = new OrderClothesForm($dbAdapter);
            $form->bind($orderclothes);

            $en_form = new OrderClothesEnForm($dbAdapter);
            $en_form->bind($orderclothesEN);

            $measurement_type = $orderclothes->typeof_measure;

            $bm_form = new BodyMeasureForm($dbAdapter);
            $cm_form = new ClothMeasureForm($dbAdapter);

            if($measurement_type==1){
                $measurements = $this->getBodyMeasureTable()->getMeasureByOrderClothId($id);
                $bm_form->bind($measurements);
            } else {
                $measurements = $this->getClotherMeasureTable()->getMeasureByOrderClothId($id);
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

            $cloth_type = $orderclothes->product_id;

            $sc_form = new TestModelForm();
            $sc_form->populateValues(array("systemcode"=>$arrayMy));

            $view = new ViewModel(array(
                'form' => $form,
                'en_form' => $en_form,
                'bm_form' => $bm_form,
                'cm_form' => $cm_form,
                'measurement_type' => $measurement_type,
                'sc_form' => $sc_form,
                'cloth_type' => (int) $cloth_type
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
            return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
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
//            var_dump($measurements);
        } else {
            $measurements = $this->getClotherMeasureTable()->getMeasure($cloth_type, $order_id);
            var_dump($measurements);
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

//            $temp2 = new BodyMeasure();
//            $temp3 = new ClotherMeasure();
//
//            $bm_form->setInputFilter($measurements->getInputFilter());
//            $cm_form->setInputFilter($temp3->getInputFilter());

            $form->setData($request->getPost());
            if($measurement_type==1){
                $bm_form->setInputFilter($measurements->getInputFilter());
                $bm_form->setData($request->getPost());
            }  else {
                $cm_form->setInputFilter($measurements->getInputFilter());
                $cm_form->setData($request->getPost());
            }

            if ($form->isValid()) {

                var_dump($form->isValid());

//                var_dump($orderclothes);
                $this->getOrderClothesTable()->saveOrderClothes($orderclothes);

                if($measurement_type==1 && $bm_form->isValid()){
//                    var_dump($bm_form->getData());
//                    var_dump($bm_form->getData());
//                    var_dump($bm_form->getMessages());
                    $this->getBodyMeasureTable()->saveBodyMeasure($measurements);
                }  else if($measurement_type==2 && $cm_form->isValid()){
                    var_dump($measurements);
                }
//
//                return $this->redirect()->toRoute('seller', array('action' => 'orderstocheck'));
            } else{

                if($measurement_type==1 && $bm_form->isValid()){
//                    var_dump($bm_form->getData());
                    var_dump($measurements);
                }  else if($measurement_type==2 && $cm_form->isValid()){
                    var_dump($measurements);
                }

//                var_dump($form->getData());


                $form->highlightErrorElements();
            }

        }

        $view = new ViewModel(array(
            'id' => $id,
            'form' => $form,
            'en_form' => $en_form,
            'bm_form' => $bm_form,
            'cm_form' => $cm_form,
            'measurement_type' => $measurement_type,
            'sc_form' => $sc_form,
            'orderclothes' => $orderclothes
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

        $tailors = $this->getOrderClothesTable()->getTailorsList();

        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username'],
            'tailors'=>$tailors
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

            $this->getOrderClothesTable()->changeCodeStatus($id, 4);
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

        $id = $this->params()->fromPost('id');
        $comment = $this->params()->fromPost('comment');
        $tailor_id = $this->params()->fromPost('tailor_id');

        if($tailor_id!=null && $tailor_id!='' && $id!=null && $id!=''){
            $this->getOrderClothesTable()->giveToTailor($id, $tailor_id, $comment);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'infitting'));
    }

    public function givetoclientAction(){
        $id = $this->params()->fromPost('id');
        $comment = $this->params()->fromPost('comment');

        if($id!=null && $id!=''){
            $this->getOrderClothesTable()->giveToClient($id, $comment);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'infitting'));
    }


    public function tasksAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/seller/tasks.phtml');
        return $view;
    }
    public function taskstartedAction(){
        $id = $this->params()->fromPost('id');

        if($id){
            $this->getTaskTable()->setTaskStarted($id);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'tasks'));
    }
    public function taskfinishedAction(){
        $id = $this->params()->fromPost('id');

        if($id){
            $this->getTaskTable()->setTaskFinished($id);
        }

        return $this->redirect()->toRoute('seller', array('action' => 'tasks'));
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
            $this->orderTable = $sm->get('Pidzhak\Model\seller\OrderTable');
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
            $this->orderclothesTable = $sm->get('Pidzhak\Model\seller\OrderClothesTable');
        }
        return $this->orderclothesTable;
    }

    public function getOrderClothesTableEn()
    {
        if (!$this->orderclothesTableEn) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTableEn = $sm->get('Pidzhak\Model\seller\OrderClothesTableEn');
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
            $this->bodyMeasureTable = $sm->get('Pidzhak\Model\seller\BodyMeasureTable');
        }
        return $this->bodyMeasureTable;
    }

    public function getClotherMeasureTable()
    {
        if (!$this->clotherMeasureTable) {
            $sm = $this->getServiceLocator();
            $this->clotherMeasureTable = $sm->get('Pidzhak\Model\seller\ClotherMeasureTable');
        }
        return $this->clotherMeasureTable;
    }

    public function getFinanceOperationsTable()
    {
        if (!$this->financeOperationsTable) {
            $sm = $this->getServiceLocator();
            $this->financeOperationsTable = $sm->get('Pidzhak\Model\seller\FinanceOperationsTable');
        }
        return $this->financeOperationsTable;
    }

    public function getCertificateTable()
    {
        if (!$this->certificateTable) {
            $sm = $this->getServiceLocator();
            $this->certificateTable = $sm->get('Pidzhak\Model\accountant\CertificateTable');
        }
        return $this->certificateTable;
    }

    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\seller\CustomerTable');
        }
        return $this->customerTable;
    }

    public function getPhoneCallTable()
    {
        if (!$this->phoneCallTable) {
            $sm = $this->getServiceLocator();
            $this->phoneCallTable = $sm->get('Pidzhak\Model\seller\PhoneCallTable');
        }
        return $this->phoneCallTable;
    }


    public function getTaskTable(){
        if (!$this->taskTable) {
            $sm = $this->getServiceLocator();
            $this->taskTable = $sm->get('Pidzhak\Model\accountant\TaskTable');
        }
        return $this->taskTable;
    }
}