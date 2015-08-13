<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:46
 */


namespace Pidzhak\Controller\redactor;

use PHPExcel_IOFactory;
use Pidzhak\Form\redactor\BodyMeasureForm;
use Pidzhak\Form\redactor\ClothMeasureForm;
use Pidzhak\Form\redactor\FileUploadForm;
use Pidzhak\Form\redactor\OrderClothesEnForm;
use Pidzhak\Form\redactor\TestModelForm;
use Pidzhak\Form\redactor\UploadForm;
use Pidzhak\Form\redactor\OrderClothesForm;
use Pidzhak\Model\redactor\OrderClothes;
use Pidzhak\Model\redactor\SystemCode;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $cycleTable;
    protected $orderTable;
    protected $orderclothesTable;
    protected $orderclothesTableEn;
    protected $systemcodeTable;
    protected $styleTable;
    protected $bodyMeasureTable;
    protected $clotherMeasureTable;
    protected $customerTable;
    protected $authservice;
    protected $taskTable;

    public function addrequestAction(){
        $request_type = $this->params()->fromPost('request_type');
        $request_body = $this->params()->fromPost('request_body');

        if($request_body!='' && $request_type!=''){
            $this->getOrderClothesTable()->addRequest($request_type, $request_body);
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/addRequest.phtml');
        return $view;
    }

    public function requestsAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/requests.phtml');
        return $view;
    }

    public function cycleslistAction()
    {
        $view = new ViewModel(array(
            'cycle' => $this->getCycleTable()->fetchAll(),
        ));
        $view->setTemplate('pidzhak/redactor/cyclesList.phtml');
        return $view;
    }

    public function fabricurlAction(){

        $url = $this->params()->fromPost('url');
        if($url!=''){
            $this->getOrderClothesTable()->setFabricUrl($url);
        }

        $url = $this->getOrderClothesTable()->getFabricUrl()['url'];

        $view = new ViewModel(array(
            'url'=>$url
        ));
        $view->setTemplate('pidzhak/redactor/fabricurl.phtml');
        return $view;
    }

    public function idsajaxAction(){

        $request = $this->getRequest();
        $response = $this->getResponse();
        $message = '';

        if ($request->isPost()) {

            $orderclothesEN = $this->getOrderClothesTableEn()->fetchAll();
            $orderClothRedactorIds=array();

            foreach ($orderclothesEN as $clothes) {
                array_push($orderClothRedactorIds, (int) $clothes->order_cloth_id);
            }

            $message=$orderClothRedactorIds;
        }

        $response->setContent(\Zend\Json\Json::encode(array('success' => 1, 'message' => $message)));
        return $response;
    }

    public function searchAction(){

        $view = new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
            'current_user_id' => $this->getAuthService()->getStorage()->read()['username']
        ));
        $view->setTemplate('pidzhak/redactor/search.phtml');
        return $view;
    }

    public function indexAction()
    {
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('pidzhak');
        }

        $orderclothesEN = $this->getOrderClothesTableEn()->fetchAll();
        $orderClothRedactorIds=array();

        foreach ($orderclothesEN as $clothes) {
            array_push($orderClothRedactorIds, (int) $clothes->order_cloth_id);
        }

        $view = new ViewModel(array(
            'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
            'ids' => $orderClothRedactorIds
        ));
        $view->setTemplate('pidzhak/redactor/index.phtml');
        return $view;
    }

    public function regcyclesAction(){
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('pidzhak');
        }

        $orderclothesEN = $this->getOrderClothesTableEn()->fetchAll();
        $orderClothRedactorIds=array();

        foreach ($orderclothesEN as $clothes) {
            array_push($orderClothRedactorIds, (int) $clothes->order_cloth_id);
        }

        $assistants = $this->getOrderClothesTable()->getAssistantsList();

        $view = new ViewModel(array(
            'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
            'ids' => $orderClothRedactorIds,
            'assistants' => $assistants,
        ));
        $view->setTemplate('pidzhak/redactor/regcycles.phtml');
        return $view;
    }

    public function urgentcyclesAction(){
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('pidzhak');
        }

        $orderclothesEN = $this->getOrderClothesTableEn()->fetchAll();
        $orderClothRedactorIds=array();

        foreach ($orderclothesEN as $clothes) {
            array_push($orderClothRedactorIds, (int) $clothes->order_cloth_id);
        }

        $assistants = $this->getOrderClothesTable()->getAssistantsList();

        $view = new ViewModel(array(
            'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
            'ids' => $orderClothRedactorIds,
            'assistants' => $assistants,
        ));
        $view->setTemplate('pidzhak/redactor/urgentcycles.phtml');
        return $view;
    }


    public function mydayAction(){
        $from_date = $this->params()->fromQuery('from_date');
        $to_date   = $this->params()->fromQuery('to_date');

        $super_submit_dead = $this->getOrderClothesTable()->getSuperSubmit();
        $super_ship_dead = $this->getOrderClothesTable()->getSuperShip();

        $fast_submit_dead = $this->getOrderClothesTable()->getFastSubmit();
        $fast_ship_dead = $this->getOrderClothesTable()->getFastShip();

        $slow_submit_dead = $this->getOrderClothesTable()->getSlowSubmit($from_date, $to_date);
        $slow_ship_dead = $this->getOrderClothesTable()->getSlowShip($from_date, $to_date);

        $view = new ViewModel(array(
            'from_date' => $from_date,
            'to_date' => $to_date,
            'super_submit_dead' => $super_submit_dead,
            'super_ship_dead' => $super_ship_dead,
            'fast_submit_dead' => $fast_submit_dead,
            'fast_ship_dead' => $fast_ship_dead,
            'slow_submit_dead' => $slow_submit_dead,
            'slow_ship_dead' => $slow_ship_dead

        ));
        $view->setTemplate('pidzhak/redactor/myday.phtml');
        return $view;
    }

    public function sendtosellerAction(){
        $id = $this->params()->fromPost('id');
        $excel_order_id = $this->params()->fromPost('order_id');

        if($id!='' && $excel_order_id!='') {
            $this->getOrderClothesTable()->sendToSeller($id, $excel_order_id);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'copypaste'));
    }

    public function backtoredactorAction(){
        $id = $this->params()->fromPost('id');
        $assist_comment = $this->params()->fromPost('assist_comment');

        if($id!='' && $assist_comment!='') {
            $this->getOrderClothesTable()->backToRedactor($id, $assist_comment);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'copypaste'));
    }

    public function setstatus3Action(){
        $id = $this->params()->fromPost('id');
        if($id!=''){
            $this->getOrderClothesTable()->setStatus3($id);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'inredactor'));
    }

    public function setstatus4Action(){
        $id = $this->params()->fromPost('id');
        if($id!=''){
            $this->getOrderClothesTable()->setStatus4($id);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'inredactor'));
    }

    public function setstatus1Action(){
        $id = $this->params()->fromPost('id');
        if($id!=''){
            $this->getOrderClothesTable()->setStatus1($id);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'inredactor'));
    }

    public function insellerAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/inseller.phtml');
        return $view;
    }

    public function inredactorAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/inredactor.phtml');
        return $view;
    }

    public function failcodecheckAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/failExcelCodeCheck.phtml');
        return $view;
    }

    public function denyclothAction(){
        $id = (int) $this->params()->fromPost('id', 0);
        if($id) {

            $this->getOrderClothesTable()->changeCodeStatus($id, 111);
            $this->getOrderClothesTable()->setStatus10redactor($id);
        }

        return $this->redirect()->toRoute('redactor', array('action' => 'index'));
    }

    public function readyforprodAction(){

        $id = $this->params()->fromQuery('id');
        $startDate = $this->params()->fromQuery('date1');
        $endDate = $this->params()->fromQuery('date2');

        if($startDate!=null && $startDate!='' && $endDate!=null && $endDate!='' && $id!=null && $id!=''){
            $this->getOrderClothesTable()->sendToProd($id, $startDate, $endDate);

            return $this->redirect()->toRoute('redactor', array('action' => 'readyforprod'));
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/readyForProduction.phtml');
        return $view;
    }

    public function readyforprodnewAction(){

        $id = $this->params()->fromPost('id');
        $startDate = $this->params()->fromPost('date1');
        $endDate = $this->params()->fromPost('date2');

        if($startDate!=null && $startDate!='' && $endDate!=null && $endDate!='' && $id!=null && $id!=''){
            $this->getOrderClothesTable()->sendToProd($id, $startDate, $endDate);
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/readyForProduction.phtml');
        return $view;
    }

    public function presubmitAction(){
        $id = (int) $this->params()->fromRoute('id', 0);

        if($id) {
            $this->getOrderClothesTable()->presubmit($id);
        }

        return $this->redirect()->toRoute('redactor', array('action' => 'index'));
    }

    public function readyforshippingAction(){

        $id = $this->params()->fromQuery('id');
        $date = $this->params()->fromQuery('date');

        if($date!=null && $date!='' && $id!=null && $id!=''){
            $this->getOrderClothesTable()->setShipDate($id, $date);

            return $this->redirect()->toRoute('redactor', array('action' => 'readyforshipping'));
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/readyForShipping.phtml');
        return $view;
    }

    public function successAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/successGoBack.phtml');
        return $view;
    }

    public function copypasteAction(){

        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('pidzhak');
        }

        $orderclothesEN = $this->getOrderClothesTableEn()->fetchAll();
        $orderClothRedactorIds=array();

        foreach ($orderclothesEN as $clothes) {
            array_push($orderClothRedactorIds, (int) $clothes->order_cloth_id);
        }

        $view = new ViewModel(array(
            'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
            'ids' => $orderClothRedactorIds
        ));

        $view->setTemplate('pidzhak/redactor/inassistant.phtml');
        return $view;
    }

    public function sendtoassitantAction(){

        $id = $this->params()->fromPost('id');
        $assist_id = $this->params()->fromPost('assist_id');

        if($id!=''){
            $this->getOrderClothesTable()->sendToAssistant($id, $assist_id);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'regcycles'));
    }

    public function entercodesAction(){

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));

        try { $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id); }
        catch (\Exception $ex) { return $this->redirect()->toRoute('redactor', array('action' => 'index')); }

        try { $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id); }
        catch (\Exception $ex) { $orderclothesEN = null; }

        try {
            $systemCode1 = $this->getSystemCodesTable()->getSystemCode($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        }
        catch (\Exception $ex) {
            $systemCode1 = null;
        }

//        $code_status = ;
//        $fabric_status = ;

        if($systemCode1!=null){
            $style = $systemCodeList;
            $arrayMy = array();

            foreach($style as $temp){
                array_push($arrayMy,
                    array(
                        'code'            => $temp->code,
                        'fabric_optional' => $temp->fabric_optional,
                        'description'     => $temp->description,
                    ));
            }
            $sc_form = new TestModelForm();
            $sc_form->populateValues(array("systemcode"=>$arrayMy));
        } else {
//            $style = $this->getStyleTable()->getStyleByIdAndClothType($orderclothes['style_id'], $orderclothes['product_id']);
            $style = $this->getStyleTable()->getStyleByIdAndClothType($orderclothes->style_id, $orderclothes->product_id);
            $arrayMy = array();

            foreach($style as $temp){
                array_push($arrayMy,
                    array(
                        'code'            => $temp->style_code,
                        'fabric_optional' => $temp->style_code_fabric,
                        'description'     => $temp->style_code_desc,
                    ));
            }

            $sc_form = new TestModelForm();
            $sc_form->populateValues(array("systemcode"=>$arrayMy));
        }

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form  = new OrderClothesForm($dbAdapter);
        $form->bind($orderclothes);

        $en_form = new OrderClothesEnForm($dbAdapter);


        $redactor_comment = '';
        if($orderclothesEN!=null){
            $en_form->bind($orderclothesEN);
            $redactor_comment = $orderclothesEN->redactor_comment;
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $sc_form->setData($request->getPost());
            $redactor_comment_2 =  $request->getPost()->redactor_comment_2;

//            $en_form->setData($request->getPost());

            if ($sc_form->isValid()) { // here dont need to check $en_form
                $systemCode = new SystemCode();
                $tempCode = $sc_form->getData()['systemcode'];

                $systemCodeTable = $this->getSystemCodesTable();
                $systemCodeTable->deleteSystemCode($id);

                foreach($tempCode as $tempCode1){
                    $temp = $tempCode1 + array("order_cloth_id" => $id);
                    $systemCode->exchangeArray($temp);
                    $systemCodeTable->saveSystemCode($systemCode);
                }

//                if($orderclothesEN==null){
                $orderclothesEN = new OrderClothes();
                $orderclothesEN->order_cloth_id       = $id;
                $orderclothesEN->cloth_type           = $orderclothes->product_id;
                $orderclothesEN->fabric_id            = $orderclothes->textile_id;
                $orderclothesEN->measurement_type     = $orderclothes->typeof_measure;
                $orderclothesEN->brand_label          = $orderclothes->label_brand;
                $orderclothesEN->monogram1_pos        = $orderclothes->first_monogram_location;
                $orderclothesEN->monogram2_pos        = $orderclothes->second_monogram_location;
                $orderclothesEN->monogram1_font       = $orderclothes->first_monogram_font;
                $orderclothesEN->monogram2_font       = $orderclothes->second_monogram_font;
                $orderclothesEN->monogram1_color_font = $orderclothes->first_monogram_font_color;
                $orderclothesEN->monogram2_color_font = $orderclothes->second_monogram_font_color;
                $orderclothesEN->monogram1_text       = $orderclothes->first_monogram_caption;
                $orderclothesEN->monogram2_text       = $orderclothes->second_monogram_caption;
                $orderclothesEN->redactor_comment     = $redactor_comment_2;
//                }


                $orderclothesENTable = $this->getOrderClothesTableEn();
                $orderclothesENTable->deleteOrderClothes($id);
                $orderclothesENTable->saveOrderClothes($orderclothesEN);

                $this->getOrderClothesTable()->changeCodeStatus($id, $this->params()->fromPost('custom_status'));
                $this->getOrderClothesTable()->changeFabricStatus($id, $this->params()->fromPost('fabric_status'));


                return $this->redirect()->toRoute('redactor', array('action' => 'success'));
            } else {
                $sc_form->highlightErrorElements();
            }
        }

        $clientName = $this->getOrderClothesTable()->getClientName2($orderclothes->order_id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        $view = new ViewModel(array(
                'form' => $form,
                'sc_form' => $sc_form,
                'en_form' => $en_form,
                'style_form'   => $style,
                'orderClothId' => $id,
                'clientName' => $clientName,
                'redactor_comment' => $redactor_comment,
                'code_status' => $orderclothes->code_status,
                'fabric_status' => $orderclothes->fabric_status,
            )
        );
        $view->setTemplate('pidzhak/redactor/enterCodes.phtml');
        return $view;
    }

    public function watchcodesAction(){

        $id = (int) $this->params()->fromRoute('id', 0);

//        var_dump($id);
        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
            $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('redactor', array('action' => 'index'));
//            var_dump($ex);
        }

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

        $request = $this->getRequest();

        if ($request->isPost()) {
            $sc_form->setData($request->getPost());

            if ($sc_form->isValid()) { // here dont need to check $en_form
                $systemCode = new SystemCode();
                $tempCode = $sc_form->getData()['systemcode'];

                $systemCodeTable = $this->getSystemCodesTable();
                $systemCodeTable->deleteSystemCode($id);

                foreach($tempCode as $tempCode1){
                    $temp = $tempCode1 + array("order_cloth_id" => $id);
                    $systemCode->exchangeArray($temp);
                    $systemCodeTable->saveSystemCode($systemCode);
                }

                return $this->redirect()->toRoute('redactor', array('action' => 'success'));
            } else {
                $sc_form->highlightErrorElements();
            }
        }

        $clientName = $this->getOrderClothesTable()->getClientName2($orderclothes->order_id);
        $clientName = $clientName->lastname.' '.$clientName->firstname.' '.$clientName->middlename;

        $cloth_type = $orderclothes->product_id;

        $view = new ViewModel(array(
                'en_form' => $en_form,
                'sc_form' => $sc_form,
                'bm_form' => $bm_form,
                'cm_form' => $cm_form,
                'measurement_type' => $measurement_type,
                'clientName' => $clientName,
                'cloth_type' => $cloth_type,
                'id'=>$id,
            )
        );
        $view->setTemplate('pidzhak/redactor/watchCodes.phtml');
        return $view;
    }

    public function comparecodesAction(){
        $view = new ViewModel(array(
//                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/redactor/compareCodes.phtml');
        return $view;
    }

    public function newcompareAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));
        $xls_data='firsttime';
        $tempFile = null;
        $form = new FileUploadForm('upload-form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();

                if($data['excel-file']['size']!=0){
                    // Form is valid, save the form!
    //                return $this->redirect()->toRoute('upload-form/success');
                    $file_name = $data['excel-file']['tmp_name'];
                    $xls_data = $this->excelReader($file_name, $id);

                    if($xls_data==''){
                        $this->getOrderClothesTable()->setStatus4($id);
                    }
                }
            } else {
                // Form not valid, but file uploads might be valid...
                // Get the temporary file information to show the user in the view
                $fileErrors = $form->get('excel-file')->getMessages();
                if (empty($fileErrors)) {
                    $tempFile = $form->get('excel-file')->getValue();
                }
            }

        }

//        var_dump($xls_data);

        $view = new ViewModel(
            array(
                'form' => $form,
                'tempFile' => $tempFile,
                'id' => $id,
                'xls_data' => $xls_data
            )
        );
        $view->setTemplate('pidzhak/redactor/newCompareCodes.phtml');
        return $view;
    }

    public function compareAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));

        $xls_data='firsttime';

        $form = new UploadForm('upload-form');
        $tempFile = null;

        $prg = $this->fileprg($form);
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                $data = $form->getData();
//                var_dump("<cr>");
//                var_dump($data);
                $file_name = $data['excel-file']['tmp_name'];
                $xls_data = $this->excelReader($file_name, $id);

                if($xls_data==''){
//                    $this->getOrderClothesTable()->setStatus4($id);
                }

                // change status to 4

//                echo $xls_data;
                // Form is valid, save the form!
                //return $this->redirect()->toRoute('upload-form/success');
            } else {
                // Form not valid, but file uploads might be valid...
                // Get the temporary file information to show the user in the view
                $fileErrors = $form->get('excel-file')->getMessages();
                if (empty($fileErrors)) {
                    $tempFile = $form->get('excel-file')->getValue();
                }
            }
        }

        $view = new ViewModel(
            array(
                'form' => $form,
                'tempFile' => $tempFile,
                'id' => $id,
                'xls_data' => $xls_data
                )
        );
        $view->setTemplate('pidzhak/redactor/compareCodes.phtml');
        return $view;
    }

    private function excelReader($filename, $id)
    {

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
            $order = $this->getOrderTable()->getOrder($orderclothes->order_id);
            $client = $this->getCustomerTable()->getCustomer($order->customer_id);
        } catch (\Exception $ex) {
            var_dump($ex);
//            return $this->redirect()->toRoute('redactor', array('action' => 'index'));
        }


        $systemClientFullName = $client->lastname.' '.$client->firstname.' '.$client->middlename;
        $systemClientLastname = $client->lastname;
        $systemClientFirstname = $client->firstname;

//        $clientNameCheck = false;

        $systemFabricNumber = $orderclothes->textile_id;
//        var_dump('fabric_number before: '.$systemFabricNumber);
        if(strpos($systemFabricNumber,'БД') !== false) $systemFabricNumber=substr($systemFabricNumber, 4);
        if(strpos($systemFabricNumber,'СЭ') !== false) $systemFabricNumber=substr($systemFabricNumber, 4);
//        var_dump('fabric_number after: '.$systemFabricNumber);

        $fabricNumberCheck = false;

        $systemMeasurementType = (int) $orderclothes->typeof_measure;
        $measurementTypeCheck = false;

        $fileCodesArray = array();
        $systemCodesArray = array();
        $systemOptionalFabricArrays = array();

        $fileMeasurements = array();
        $systemMeasurements = array();

        $systemMonogram1 = $orderclothes->first_monogram_caption;
        $systemMonogram2 = $orderclothes->second_monogram_caption;

//        var_dump($systemMonogram1);
//        var_dump($systemMonogram1);

        $codesAmountError = '';
        $codesError = '';
        $fabricNumberError = '';
        $systemOptionalFabricError='';
        $measurementTypeError = '';
        $measurementsAmountError = '';
        $measurementsError = '';
        $clientNameError = '';
        $monogramError1 = '';
        $monogramError2 = '';


        foreach($systemCodeList as $temp){
            array_push($systemCodesArray, trim($temp->code));
            if($temp->fabric_optional!=null && $temp->fabric_optional!='no')
                array_push($systemOptionalFabricArrays, $temp->fabric_optional);
        }

//        var_dump($systemOptionalFabricArrays);

        if($orderclothes->product_id==1 || $orderclothes->product_id==2 || $orderclothes->product_id==3 || $orderclothes->product_id==4 || $orderclothes->product_id==5){
            $withMesaurements = true;
        } else {
            $withMesaurements = false;
        }

        if($withMesaurements){
            if($systemMeasurementType==1){
                $measurements = $this->getBodyMeasureTable()->getBMbyOCIDandClothType($id, $orderclothes->product_id);
                array_push($systemMeasurements, $measurements->growth);
                array_push($systemMeasurements, $measurements->weight);
                array_push($systemMeasurements, $measurements->arm_position);
                array_push($systemMeasurements, $measurements->neck);
                array_push($systemMeasurements, $measurements->chest);
                array_push($systemMeasurements, $measurements->stomach);
                array_push($systemMeasurements, $measurements->seat);
                array_push($systemMeasurements, $measurements->thigh);
                array_push($systemMeasurements, $measurements->knee_finished);
                array_push($systemMeasurements, $measurements->pant_bottom_finished);
                array_push($systemMeasurements, $measurements->otseam_l_and_r);
                array_push($systemMeasurements, $measurements->nape_to_waist);
                array_push($systemMeasurements, $measurements->front_waist_length);
                array_push($systemMeasurements, $measurements->back_waist_height);
                array_push($systemMeasurements, $measurements->front_waist_height);
                array_push($systemMeasurements, $measurements->biceps);
                array_push($systemMeasurements, $measurements->back_shoulder);
                array_push($systemMeasurements, $measurements->right_sleeve);
                array_push($systemMeasurements, $measurements->left_sleeve);
                array_push($systemMeasurements, $measurements->back_length);
                array_push($systemMeasurements, $measurements->overcoat_back_length);
                array_push($systemMeasurements, $measurements->waist);
                array_push($systemMeasurements, $measurements->right_wrist);
                array_push($systemMeasurements, $measurements->left_wrist);
                array_push($systemMeasurements, $measurements->butt_position);
                array_push($systemMeasurements, $measurements->u_rise_auto);
                array_push($systemMeasurements, 1);
            } else {
                $measurements = $this->getClotherMeasureTable()->getCMbyOCIDandClothType($id, $orderclothes->product_id);
                array_push($systemMeasurements, $measurements->growth);
                array_push($systemMeasurements, $measurements->weight);
                array_push($systemMeasurements, $measurements->chest_finished);
                array_push($systemMeasurements, $measurements->stomach_finished);
                array_push($systemMeasurements, $measurements->jacket_seat_finished);
                array_push($systemMeasurements, $measurements->biceps_finished);
                array_push($systemMeasurements, $measurements->left_sleeve_finished);
                array_push($systemMeasurements, $measurements->right_sleeve_finished);
                array_push($systemMeasurements, $measurements->back_length_finished);
                array_push($systemMeasurements, $measurements->front_length_finished);
                array_push($systemMeasurements, $measurements->shoulder_finished);
                array_push($systemMeasurements, $measurements->waist_finished);
                array_push($systemMeasurements, $measurements->seat_finished);
                array_push($systemMeasurements, $measurements->thigh_finished);
                array_push($systemMeasurements, $measurements->outseam_l_and_r_finished);
                array_push($systemMeasurements, $measurements->knee_finished);
                array_push($systemMeasurements, $measurements->pant_bottom_finished);
                array_push($systemMeasurements, $measurements->u_rise_finished);
                array_push($systemMeasurements, $measurements->right_cuff);
                array_push($systemMeasurements, $measurements->left_cuff);
                array_push($systemMeasurements, $measurements->shirt_neck);
                array_push($systemMeasurements, 1);
            }

            $systemMeasurements = $this->removeNulls($systemMeasurements);
        }

        $result_str = '';
        if (!file_exists($filename)){
            return $result_str;
        }

        $objPHPExcel = PHPExcel_IOFactory::load($filename);

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            foreach ($worksheet->getRowIterator() as $row) {

                $cellIterator = $row->getCellIterator();
                foreach ($cellIterator as $cell) {
                    if (!is_null($cell)) {

                        if(strpos($cell->getCalculatedValue(), ':') !== false){
                            array_push($fileCodesArray, substr($cell->getCalculatedValue(), 0, 4));
                        }

                        if($systemMonogram1!='' && $systemMonogram1!=null && strpos($cell->getCalculatedValue(), $systemMonogram1) == false){
                            $monogramError1 = 'monogramerror1';
//                            var_dump($systemMonogram1);
//                            var_dump($systemMonogram2);
                        }

                        if($systemMonogram2!='' && $systemMonogram2!=null && strpos($cell->getCalculatedValue(), $systemMonogram2) == false){
                            $monogramError2 = 'monogramerror2';
//                            var_dump($systemMonogram1);
//                            var_dump($systemMonogram2);
                        }

                        if($withMesaurements) {
                            if (strpos($cell->getCalculatedValue(), 'Body Measurements') !== false && $systemMeasurementType == 1) {
                                $measurementTypeCheck = true;
                            }
                            if (strpos($cell->getCalculatedValue(), 'Finished Measurements') !== false && $systemMeasurementType == 2) {
                                $measurementTypeCheck = true;
                            }
                        }

                        if($withMesaurements) {
                            if (is_numeric($cell->getCalculatedValue())) {
                                array_push($fileMeasurements, $cell->getCalculatedValue());
                            }
                        }

//                        if (strpos($cell->getCalculatedValue(), $systemClientLastname) !== false && strpos($cell->getCalculatedValue(), $systemClientFirstname) !== false) {
//                            $clientNameCheck = true;
//                        }

                        if($cell->getCalculatedValue()==$systemFabricNumber){
                            $fabricNumberCheck = true;
                        }

                        if(sizeof($systemOptionalFabricArrays)>0){
                            for($i=0; $i<sizeof($systemOptionalFabricArrays);$i++){
                                if(strpos($cell->getCalculatedValue(), trim($systemOptionalFabricArrays[$i])) !== false)
                                    $systemOptionalFabricArrays[$i]='deleted';
                            }
                        }

                        $result_str = $result_str .$cell->getCalculatedValue()." ";
                    }
                }
            }
        }


//        var_dump($systemCodesArray);
//        var_dump($fileCodesArray);
//
//        var_dump(array_diff($systemCodesArray, $fileCodesArray));
//        var_dump(array_diff($fileCodesArray, $systemCodesArray));

        $counter_for_SOPFA = 0;

        for($i=0; $i<sizeof($systemOptionalFabricArrays);$i++){
            if($systemOptionalFabricArrays[$i]!='deleted') $counter_for_SOPFA++;
        }

        $tempError = $this->compareArrays($fileCodesArray, $systemCodesArray);
        if($tempError=='different amount'){
            $codesAmountError = 'Количества кодов в системе отличается от количества кодов в файле';
            $codesError = 'Коды в системе отличаются от кодов в файле';
        } else if($tempError=='different elements'){
            $codesError = 'Коды в системе отличаются от кодов в файле';
        }

        if($withMesaurements) {
//            $tempError = $this->compareArrays($fileMeasurements, $systemMeasurements);
            if(count(array_diff($systemMeasurements, $fileMeasurements))>0){
                $measurementsError = 'Замеры в системе отличаются от замеров в файле';
            }
//            if ($tempError == 'different amount') {
////                $measurementsAmountError = 'Количества замеров в системе отличается от количества замеров в файле';
////                $measurementsError = 'Замеры в системе отличаются от замеров в файле';
//            } else if ($tempError == 'different elements') {
//                $measurementsError = 'Замеры в системе отличаются от замеров в файле';
//            }
        }

//        if(!$clientNameCheck){
//            $clientNameError='ФИО клиента в системе отличается от ФИО клиента в файле';
//        }

        if(!$fabricNumberCheck){
            $fabricNumberError='Номер ткани в системе отличается от номера ткани в файле';
        }

//        if($withMesaurements) {
//            if (!$measurementTypeCheck) {
//                $measurementTypeError = 'Тип замера в системе отличается от типа замера в файле';
//            }
//        }

        if($counter_for_SOPFA!=0){
            $systemOptionalFabricError='Ткани в системных кодах отличаются';
        }

        if($systemMonogram1!='' && $systemMonogram1!=null){
            $monogramError1 = 'monogramerror1';
        }
        if($systemMonogram2!='' && $systemMonogram2!=null){
            $monogramError2 = 'monogramerror2';
        }

        $result_array = array(
            'codesError'=>$codesError,
            'codesAmountError'=>$codesAmountError,
            'measurementsError'=>$measurementsError,
            'measurementsAmountError'=>$measurementsAmountError,
            'clientNameError'=>$clientNameError,
            'measurementTypeError'=>$measurementTypeError,
            'fabricNumberError'=>$fabricNumberError,
            'systemOptionalFabricError'=>$systemOptionalFabricError,
            'monogramError1'=>$monogramError1,
            'monogramError2'=>$monogramError2,
        );

        if($codesError=='' && $measurementsError=='' && $clientNameError=='' && $measurementTypeError=='' && $fabricNumberError=='' && $systemOptionalFabricError=='')
            return '';
        else
            return $result_array;
    }




    public function tasksAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/tasks.phtml');
        return $view;
    }
    public function taskstartedAction(){
        $id = $this->params()->fromPost('id');

        if($id){
            $this->getTaskTable()->setTaskStarted($id);
        }

        return $this->redirect()->toRoute('redactor', array('action' => 'tasks'));
    }
    public function taskfinishedAction(){
        $id = $this->params()->fromPost('id');

        if($id){
            $this->getTaskTable()->setTaskFinished($id);
        }

        return $this->redirect()->toRoute('redactor', array('action' => 'tasks'));
    }


    public function basketregAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/fabrics/basketRegular.phtml');
        return $view;
    }
    public function basketurgentAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/fabrics/basketUrgent.phtml');
        return $view;
    }
    public function comingregAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/fabrics/comingRegular.phtml');
        return $view;
    }
    public function comingurgentAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/fabrics/comingUrgent.phtml');
        return $view;
    }
    public function fabricreadyAction(){
        $id = $this->params()->fromPost('id');

        if($id!='' && $id!=null){
            $this->getOrderClothesTable()->setFabricReady($id);
        }
        return $this->redirect()->toRoute('redactor', array('action' => 'comingreg'));
    }



    public function getOrderClothesTable()
    {
        if (!$this->orderclothesTable) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTable = $sm->get('Pidzhak\Model\seller\OrderClothesTable');
        }
        return $this->orderclothesTable;
    }

    public function getSystemCodesTable(){
        if (!$this->systemcodeTable) {
            $sm = $this->getServiceLocator();
            $this->systemcodeTable = $sm->get('Pidzhak\Model\redactor\SystemCodeTable');
        }
        return $this->systemcodeTable;
    }

    public function getStyleTable(){
        if (!$this->styleTable) {
            $sm = $this->getServiceLocator();
            $this->styleTable = $sm->get('Pidzhak\Model\admin\StyleTable');
        }
        return $this->styleTable;
    }

    public function getOrderClothesTableEn()
    {
        if (!$this->orderclothesTableEn) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTableEn = $sm->get('Pidzhak\Model\seller\OrderClothesTableEn');
        }
        return $this->orderclothesTableEn;
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

    public function getOrderTable()
    {
        if (!$this->orderTable) {
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Pidzhak\Model\seller\OrderTable');
        }
        return $this->orderTable;
    }

    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\seller\CustomerTable');
        }
        return $this->customerTable;
    }

    public function removeNulls($array){
        foreach($array as $elem){
            if($elem==null) unset($array[array_search($elem, $array)]);
        }
        return $array;
    }

    public function compareArrays($array1, $array2){
        $result = '';

        if(count($array1)==count($array2)){
            if(count(array_diff($array1, $array2))>0 || count(array_diff($array2, $array1))>0)
                $result = 'different elements';
        } else {
            $result = 'different amount';
        }

        return $result;
    }

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function getCycleTable(){
        if (!$this->cycleTable) {
            $sm = $this->getServiceLocator();
            $this->cycleTable = $sm->get('Pidzhak\Model\admin\CycleTable');
        }
        return $this->cycleTable;
    }

    public function getTaskTable(){
        if (!$this->taskTable) {
            $sm = $this->getServiceLocator();
            $this->taskTable = $sm->get('Pidzhak\Model\accountant\TaskTable');
        }
        return $this->taskTable;
    }
}