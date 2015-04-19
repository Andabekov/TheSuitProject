<?php
namespace Pidzhak\Controller\Seller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\Seller\Seller;
use Pidzhak\Form\Seller\SellerForm;

class SellerController extends AbstractActionController
{
    protected $sellerTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'sellers' => $this->getSellerTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new SellerForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $seller = new Seller();
            $form->setInputFilter($seller->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $seller->exchangeArray($form->getData());
                $this->getSellerTable()->saveSeller($seller);

                // Redirect to list of sellers
                return $this->redirect()->toRoute('seller');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('seller', array(
                'action' => 'add'
            ));
        }

        try {
            $seller = $this->getSellerTable()->getSeller($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('seller', array(
                'action' => 'index'
            ));
        }

        $form  = new SellerForm();
        $form->bind($seller);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($seller->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSellerTable()->saveSeller($seller);

                // Redirect to list of sellers
                return $this->redirect()->toRoute('seller');
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
            return $this->redirect()->toRoute('seller');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getSellerTable()->deleteSeller($id);
            }

            // Redirect to list of sellers
            return $this->redirect()->toRoute('seller');
        }

        return array(
            'id'    => $id,
            'seller' => $this->getSellerTable()->getSeller($id)
        );
    }

    /*Inversion of Control*/
    public function getSellerTable()
    {
        if (!$this->sellerTable) {
            $sm = $this->getServiceLocator();
            $this->sellerTable = $sm->get('Pidzhak\Model\Seller\SellerTable');
        }
        return $this->sellerTable;
    }
}