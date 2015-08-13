<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:53
 */

namespace Pidzhak\Controller\delivery;

use PHPExcel_IOFactory;
use Pidzhak\Form\redactor\BodyMeasureForm;
use Pidzhak\Form\redactor\ClothMeasureForm;
use Pidzhak\Form\redactor\FileUploadForm;
use Pidzhak\Form\redactor\OrderClothesEnForm;
use Pidzhak\Form\redactor\TestModelForm;
use Pidzhak\Model\redactor\SystemCode;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $orderclothesTable;
    protected $orderclothesTableEn;
    protected $systemcodeTable;
    protected $bodyMeasureTable;
    protected $clotherMeasureTable;
    protected $orderTable;
    protected $customerTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
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

        $view->setTemplate('pidzhak/delivery/index.phtml');
        return $view;
    }

    public function successAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/seller/successGoBack.phtml');
        return $view;
    }

    public function watchcodesAction(){

        $id = (int) $this->params()->fromRoute('id', 0);

//        var_dump($id);
        if (!$id) return $this->redirect()->toRoute('delivery', array('action' => 'index'));

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
            $orderclothesEN = $this->getOrderClothesTableEn()->getOrderClothes($id);
            $systemCodeList = $this->getSystemCodesTable()->getSystemCodeList($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('delivery', array('action' => 'index'));
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

                return $this->redirect()->toRoute('delivery', array('action' => 'success'));
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
        $view->setTemplate('pidzhak/delivery/watchCodes.phtml');
        return $view;
    }

    public function sendtosellerAction(){
        $id = $this->params()->fromPost('id');
        $excel_order_id = $this->params()->fromPost('order_id');

        if($id!='' && $excel_order_id!='') {
            $this->getOrderClothesTable()->sendToSeller($id, $excel_order_id);
        }
        return $this->redirect()->toRoute('delivery');
    }

    public function backtoredactorAction(){
        $id = $this->params()->fromPost('id');
        $assist_comment = $this->params()->fromPost('assist_comment');

        if($id!='' && $assist_comment!='') {
            $this->getOrderClothesTable()->backToRedactor($id, $assist_comment);
        }
        return $this->redirect()->toRoute('delivery');
    }

    public function insellerAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/redactor/inseller.phtml');
        return $view;
    }

    public function inredactorAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/delivery/inAssistant.phtml');
        return $view;
    }

    public function newcompareAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('delivery', array('action' => 'index'));
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
        $view->setTemplate('pidzhak/delivery/compareCodes.phtml');
        return $view;
    }

    public function setstatus17Action(){
        $id = $this->params()->fromPost('id');
        if($id!=''){
            $this->getOrderClothesTable()->setStatus17($id);
        }
        return $this->redirect()->toRoute('delivery', array('action' => 'inredactor'));
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

    // GET TABLE FUNCTIONS
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
}