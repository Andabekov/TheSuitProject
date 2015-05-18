<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:55
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\StyleForm;
use Pidzhak\Form\redactor\TestModelForm;
use Pidzhak\Model\admin\Style;
use Pidzhak\Model\redactor\SystemCode;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StyleController extends AbstractActionController
{
    protected $styleTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'style' => $this->getStyleTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/styles.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new StyleForm();

        $request = $this->getRequest();

        $sc_form = new TestModelForm();

        if ($request->isPost()) {
            $style = new Style();
            $form->setInputFilter($style->getInputFilter());
            $form->setData($request->getPost());
            $sc_form->setData($request->getPost());

            if ($form->isValid() && $sc_form->isValid()) {

                $style_number = $form->getData()['style_num'];
                $cloth_type = $form->getData()['cloth_type'];
                $styleCodes = $sc_form->getData()['systemcode'];

                $style_table = $this->getStyleTable();

                foreach($styleCodes as $codes){

                    $newStyle = array("style_num" => $style_number,
                                      "cloth_type" => $cloth_type,
                                      "style_code"=>$codes['code'],
                                      "style_code_fabric"=>$codes['fabric_optional'],
                                      "style_code_desc"=>$codes['description'],
                    );
                    $style->exchangeArray($newStyle);
                    $style_table->saveStyle($style);
                }

                return $this->redirect()->toRoute('styles');

            }else {
                $form->highlightErrorElements();
                $sc_form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form, 'sc_form' => $sc_form));
        $view->setTemplate('pidzhak/admin/addStyle.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('styles', array('action' => 'add'));
        }

        try {
            $style = $this->getStyleTable()->getStyle($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('styles', array('action' => 'index'));
        }

        $form  = new StyleForm();
        $form->bind($style);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $styleCodes = $this->getStyleTable()->getStyleCodeList($style->style_num, $style->cloth_type);
        $arrayMy = array();

        foreach($styleCodes as $temp){
            array_push($arrayMy,
                array(
                    'code'            => $temp->style_code,
                    'fabric_optional' => $temp->style_code_fabric,
                    'description'     => $temp->style_code_desc,
                ));
        }

        $sc_form = new TestModelForm();
        $sc_form->populateValues(array("systemcode"=>$arrayMy));

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($style->getInputFilter());
            $form->setData($request->getPost());
            $sc_form->setData($request->getPost());

            if ($form->isValid() && $sc_form->isValid()) {



                $style_number = $style->style_num;
                $cloth_type = $style->cloth_type;
                $styleCodes = $sc_form->getData()['systemcode'];

                $style_table = $this->getStyleTable();

                $style_table->deleteStyleList($style->style_num, $style->cloth_type);

                foreach($styleCodes as $codes){

                    $newStyle = array("style_num" => $style_number,
                        "cloth_type" => $cloth_type,
                        "style_code"=>$codes['code'],
                        "style_code_fabric"=>$codes['fabric_optional'],
                        "style_code_desc"=>$codes['description'],
                    );
                    $style->exchangeArray($newStyle);
                    $style_table->saveStyle($style);
                }

                return $this->redirect()->toRoute('styles');

            }else {
                $form->highlightErrorElements();
                $sc_form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'sc_form' => $sc_form,
            )
        );
        $view->setTemplate('pidzhak/admin/editStyle.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('styles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getStyleTable()->deleteStyle($id);
            return $this->redirect()->toRoute('styles');
        }

        return array(
            'id'    => $id,
            'style' => $this->getStyleTable()->getStyle($id)
        );
    }

    public function getStyleTable(){
        if (!$this->styleTable) {
            $sm = $this->getServiceLocator();
            $this->styleTable = $sm->get('Pidzhak\Model\admin\StyleTable');
        }
        return $this->styleTable;
    }
}