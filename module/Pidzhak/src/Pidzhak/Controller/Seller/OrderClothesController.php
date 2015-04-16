<?php
namespace Pidzhak\Controller\Seller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\Seller\OrderClothes;
use Pidzhak\Form\OrderClothesForm;

class OrderClothesController extends AbstractActionController
{
    protected $orderclothesTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'orderclothes' => $this->getOrderClothesTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new OrderClothesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $orderclothes = new OrderClothes();
            $form->setInputFilter($orderclothes->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $orderclothes->exchangeArray($form->getData());
                $this->getOrderClothesTable()->saveOrderClothes($orderclothes);

                return $this->redirect()->toRoute('orderclothes');
            }else {
                $form->highlightErrorElements();
                // other error logic
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('orderclothes', array(
                'action' => 'add'
            ));
        }

        try {
            $orderclothes = $this->getOrderClothesTable()->getOrderClothes($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('orderclothes', array(
                'action' => 'index'
            ));
        }

        $form  = new OrderClothesForm();
        $form->bind($orderclothes);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($orderclothes->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getOrderClothesTable()->saveOrderClothes($orderclothes);

                return $this->redirect()->toRoute('orderclothes');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('orderclothes');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getOrderClothesTable()->deleteOrderClothes($id);
            }

            return $this->redirect()->toRoute('orderclothes');
        }

        return array(
            'id'    => $id,
            'orderclothes' => $this->getOrderClothesTable()->getOrderClothes($id)
        );
    }

    /*Inversion of Control*/
    public function getOrderClothesTable()
    {
        if (!$this->orderTable) {
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Pidzhak\Model\Seller\OrderClothesTable');
        }
        return $this->orderTable;
    }
}