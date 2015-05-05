<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:46
 */


namespace Pidzhak\Controller\redactor;

use PHPExcel_IOFactory;
use Pidzhak\Form\redactor\SystemCodeForm;
use Pidzhak\Form\Redactor\UploadForm;
use Pidzhak\Form\Redactor\OrderClothesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $orderclothesTable;

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

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form  = new OrderClothesForm($dbAdapter);
        $form->bind($orderclothes);

        $sc_form = new SystemCodeForm();

        $request = $this->getRequest();


        $view = new ViewModel(array(
                'form' => $form,
                'sc_form' => $sc_form,
            )
        );
        $view->setTemplate('pidzhak/redactor/enterCodes.phtml');
        return $view;
    }

    public function watchcodesAction(){
        $view = new ViewModel(array(
//                'form' => $form,
//                'back' => '/clients',
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
}