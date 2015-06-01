<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:46
 */


namespace Pidzhak\Controller\redactor;

use PHPExcel_IOFactory;
use Pidzhak\Form\admin\StyleForm;
use Pidzhak\Form\Redactor\BodyMeasureForm;
use Pidzhak\Form\Redactor\ClothMeasureForm;
use Pidzhak\Form\Redactor\OrderClothesEnForm;
use Pidzhak\Form\redactor\TestModelForm;
use Pidzhak\Form\Redactor\UploadForm;
use Pidzhak\Form\Redactor\OrderClothesForm;
use Pidzhak\Model\redactor\OrderClothes;
use Pidzhak\Model\redactor\SystemCode;
use Pidzhak\Model\redactor\TestModel;
use Pidzhak\Model\Seller\BodyMeasure;
use Pidzhak\Model\Seller\ClotherMeasure;
use Zend\Http\Client\Adapter\Test;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $orderTable;
    protected $orderclothesTable;
    protected $orderclothesTableEn;
    protected $systemcodeTable;
    protected $styleTable;
    protected $bodyMeasureTable;
    protected $clotherMeasureTable;
    protected $customerTable;

    public function indexAction()
    {
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/index.phtml');
        return $view;
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

    public function entercodesAction(){

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('redactor', array('action' => 'index'));
        }

        try {
            $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
        }
        catch (\Exception $ex) {
            $orderclothesEN = null;
        }

        try {
            $systemCode1 = $this->getSystemCodesTable()->getSystemCode($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        }
        catch (\Exception $ex) {
            $systemCode1 = null;
        }

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
            $style = $this->getStyleTable()->getStyleByIdAndClothType($orderclothes['style_id'], $orderclothes['product_id']);
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

        if($orderclothesEN!=null){
            $en_form->bind($orderclothesEN);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $sc_form->setData($request->getPost());
            $en_form->setData($request->getPost());

            if ($sc_form->isValid() && $en_form->isValid()) {
                $systemCode = new SystemCode();
                $tempCode = $sc_form->getData()['systemcode'];

                $systemCodeTable = $this->getSystemCodesTable();
                $systemCodeTable->deleteSystemCode($id);

                foreach($tempCode as $tempCode1){
                    $temp = $tempCode1 + array("order_cloth_id" => $id);
                    $systemCode->exchangeArray($temp);
                    $systemCodeTable->saveSystemCode($systemCode);
                }

                if($orderclothesEN==null){
                    $orderclothesEN = new OrderClothes();
                    $orderclothesEN->exchangeArray($en_form->getData());
                }

                $orderclothesENTable = $this->getOrderClothesTableEn();
                $orderclothesENTable->deleteOrderClothes($id);
                $orderclothesENTable->saveOrderClothes($orderclothesEN);

                return $this->redirect()->toRoute('redactor', array('action' => 'index'));
            } else {
//                var_dump($en_form->getMessages());
//                var_dump($sc_form->getMessages());
                $sc_form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
                'form' => $form,
                'sc_form' => $sc_form,
                'en_form' => $en_form,
                'style_form'   => $style,
                'orderClothId' => $id,
            )
        );
        $view->setTemplate('pidzhak/redactor/enterCodes.phtml');
        return $view;
    }

    public function watchcodesAction(){

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('redactor', array('action' => 'index'));

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
            $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('redactor', array('action' => 'index'));
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
        $cloth_type = $orderclothesEN->cloth_type;
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

        $view = new ViewModel(array(
                'en_form' => $en_form,
                'sc_form' => $sc_form,
                'bm_form' => $bm_form,
                'cm_form' => $cm_form,
                'measurement_type' => $measurement_type
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
        $clientNameCheck = false;

        $systemFabricNumber = $orderclothes->textile_id;
        $fabricNumberCheck = false;

        $systemMeasurementType = $orderclothes->typeof_measure;
        $measurementTypeCheck = false;

        $fileCodesArray = array();
        $systemCodesArray = array();

        $fileMeasurements = array();
        $systemMeasurements = array();

        $codesError = '';
        $measurementsError = '';
        $clientNameError = '';
        $measurementTypeError = '';
        $fabricNumberError = '';

        foreach($systemCodeList as $temp){
            array_push($systemCodesArray, $temp->code);
            if($temp->fabric_optional!=null)
                array_push($systemCodesArray, $temp->fabric_optional);
        }

        if($systemMeasurementType==1){
            $measurements = $this->getBodyMeasureTable()->getMeasure($orderclothes->product_id, $orderclothes->order_id);
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
            $measurements = $this->getClotherMeasureTable()->getMeasure($orderclothes->product_id, $orderclothes->order_id);
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
            array_push($systemMeasurements, $measurements->outsteam_l_and_r_finished);
            array_push($systemMeasurements, $measurements->knee_finished);
            array_push($systemMeasurements, $measurements->pant_bottom_finished);
            array_push($systemMeasurements, $measurements->u_rise_finished);
            array_push($systemMeasurements, $measurements->right_cuff);
            array_push($systemMeasurements, $measurements->left_cuff);
            array_push($systemMeasurements, $measurements->shirt_neck);
            array_push($systemMeasurements, 1);
        }

        $systemMeasurements = $this->removeNulls($systemMeasurements);

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

                        if(strpos($cell->getCalculatedValue(), 'Body Measurements') !== false){
                            if($systemMeasurementType==1) $measurementTypeCheck = true;
                        }

                        if(strpos($cell->getCalculatedValue(), 'Finished Measurements') !== false){
                            if($systemMeasurementType==2) $measurementTypeCheck = true;
                        }

                        if(is_numeric($cell->getCalculatedValue())){
                            array_push($fileMeasurements, $cell->getCalculatedValue());
                        }

                        if($cell->getCalculatedValue()==$systemClientFullName){
                            $clientNameCheck = true;
                        }

                        if($cell->getCalculatedValue()==$systemFabricNumber){
                            $fabricNumberCheck = true;
                        }

                        $result_str = $result_str .$cell->getCalculatedValue()." ";
                    }
                }
            }
        }

//        var_dump($fileCodesArray);
//        var_dump($systemCodesArray);
        $codesError = $this->compareArrays($fileCodesArray, $systemCodesArray);
        if($codesError=='different amount'){
            $codesError = 'Количества кодов в системе отличается от количества кодов в файле';
        } else if($codesError=='different elements'){
            $codesError = 'Коды в системе отличаются от кодов в файле';
        }

//        var_dump($fileMeasurements);
//        var_dump($systemMeasurements);
        $measurementsError = $this->compareArrays($fileMeasurements, $systemMeasurements);
        if($measurementsError=='different amount'){
            $measurementsError = 'Количества замеров в системе отличается от количества замеров в файле';
        } else if($codesError=='different elements'){
            $measurementsError = 'Замеры в системе отличаются от замеров в файле';
        }

        if(!$clientNameCheck){
            $clientNameError='ФИО клиента в системе отличается от ФИО клиента в файле';
        }

        if(!$fabricNumberCheck){
            $fabricNumberError='Номер ткани в системе отличается от номера ткани в файле';
        }

        if(!$measurementTypeCheck){
            $measurementTypeError='Тип замера в системе отличается от типа замера в файле';
        }

//        var_dump($codesError);
//        var_dump($measurementsError);
//
//        var_dump($systemMeasurementType);
//        var_dump($clientNameCheck);

        $result_array = array(
            'codesError'=>$codesError,
            'measurementsError'=>$measurementsError,
            'clientNameError'=>$clientNameError,
            'measurementTypeError'=>$measurementTypeError,
            'fabricNumberError'=>$fabricNumberError);

        return $result_array;
    }

    public function getOrderClothesTable()
    {
        if (!$this->orderclothesTable) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTable = $sm->get('Pidzhak\Model\Seller\OrderClothesTable');
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
            $this->orderclothesTableEn = $sm->get('Pidzhak\Model\Seller\OrderClothesTableEn');
        }
        return $this->orderclothesTableEn;
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
}