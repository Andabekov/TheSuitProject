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

    protected $orderclothesTable;
    protected $orderclothesTableEn;
    protected $systemcodeTable;
    protected $styleTable;
    protected $bodyMeasureTable;
    protected $clotherMeasureTable;

    public function indexAction()
    {
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/index.phtml');
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
//                'back' => '/clients',
            )
        );
        $view->setTemplate('pidzhak/redactor/compareCodes.phtml');
        return $view;
    }

    public function compareAction()
    {
        $form = new UploadForm('upload-form');
        $tempFile = null;

        $prg = $this->fileprg($form);
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                $data = $form->getData();
                var_dump("<cr>");
                var_dump($data);
                $file_name = $data['excel-file']['tmp_name'];
                $xls_data = $this->excelReader($file_name);
                echo $xls_data;
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

        $view = new ViewModel(array('form' => $form,
                'tempFile' => $tempFile,)
        );
        $view->setTemplate('pidzhak/redactor/upload-form.phtml');
        return $view;
    }


    private function excelReader($filename)
    {
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
                        $result_str = $result_str .$cell->getCalculatedValue()." ";
                    }
                }
            }
        }

        return $result_str;
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
}