<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 14:37
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\PriceForm;
use Pidzhak\Model\admin\Price;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PriceController extends AbstractActionController
{
    protected $priceTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'prices' => $this->getPriceTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/prices.phtml');
        return $view;
    }

    public function ajaxaddAction(){
        $form = new PriceForm();
        $request = $this->getRequest();
        $response   = $this->getResponse();
        $messages = array();

        if ($request->isPost()) {

            $price = new Price();
            $form->setInputFilter($price->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $message = 'form valid';
            }else {
                $errors = $form->getMessages();
                foreach($errors as $key=>$row)
                {
                    if (!empty($row) && $key != 'submit') {
                        foreach($row as $keyer => $rower)
                        {
                            //save error(s) per-element that
                            //needed by Javascript
                            $messages[$key][] = $rower;
                        }
                    }
                }
            }

            if (!empty($messages)){
                $response->setContent(\Zend\Json\Json::encode($messages));
            } else {
                $response->setContent(\Zend\Json\Json::encode(array('success'=>1,'hello'=>$message)));
            }

            return $response;
        }
    }

    public function addAction()
    {
        $form = new PriceForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $price = new Price();
            $form->setInputFilter($price->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $price->exchangeArray($form->getData());
                $this->getPriceTable()->savePrice($price);

                return $this->redirect()->toRoute('prices');
            }else {
                $form->highlightErrorElements();

            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/addPrice.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('prices', array('action' => 'add'));
        }

        try {
            $price = $this->getPriceTable()->getPrice($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('prices', array('action' => 'index'));
        }

        $form  = new PriceForm();
        $form->bind($price);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($price->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPriceTable()->savePrice($price);
                return $this->redirect()->toRoute('prices');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editPrice.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('prices');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getPriceTable()->deletePrice($id);
            return $this->redirect()->toRoute('prices');
        }

        return array(
            'id'    => $id,
            'price' => $this->getPriceTable()->getPrice($id)
        );
    }

    public function getPriceTable(){
        if (!$this->priceTable) {
            $sm = $this->getServiceLocator();
            $this->priceTable = $sm->get('Pidzhak\Model\admin\PriceTable');
        }
        return $this->priceTable;
    }
}