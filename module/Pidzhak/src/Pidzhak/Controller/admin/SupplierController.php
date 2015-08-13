<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:55
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\ConnectionForm;
use Pidzhak\Model\admin\Connection;
use Pidzhak\Form\admin\SupplierForm;
use Pidzhak\Model\admin\Supplier;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SupplierController extends AbstractActionController
{
    protected $supplierTable;
    protected $connectionTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
            )
        );
        $view->setTemplate('pidzhak/admin/suppliers.phtml');
        return $view;
    }

    public function connectionsAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/admin/connections.phtml');
        return $view;
    }

    public function addAction()
    {
        $supplier_name = $this->params()->fromPost('supplier_name');
        $supplier_email = $this->params()->fromPost('supplier_email');
        $email_content = $this->params()->fromPost('email_content');

        if($supplier_name!='' && $supplier_email!='' && $email_content!=''){
            $supplier = new Supplier();

            $supplier->supplier_name = $supplier_name;
            $supplier->supplier_email = $supplier_email;
            $supplier->email_content = $email_content;

            $this->getSupplierTable()->saveSupplier($supplier);
        }

        $view = new ViewModel();
        $view->setTemplate('pidzhak/admin/addSuppliers.phtml');
        return $view;
    }

    public function createAction(){

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new ConnectionForm($dbAdapter);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $connection = new Connection();
            $form->setInputFilter($connection->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $connection->exchangeArray($form->getData());
                $this->getConnectionTable()->saveConnection($connection);

                return $this->redirect()->toRoute('suppliers', array('action' => 'connections'));
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('pidzhak/admin/createConnection.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('suppliers', array('action' => 'add'));
        }

        try {
            $supplier = $this->getSupplierTable()->getSupplier($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('suppliers', array('action' => 'index'));
        }

        $form  = new SupplierForm();
        $form->bind($supplier);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($supplier->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSupplierTable()->saveSupplier($supplier);
                return $this->redirect()->toRoute('suppliers');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editSupplier.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params()->fromPost('id');

        $this->getSupplierTable()->deleteSupplier($id);
        return $this->redirect()->toRoute('suppliers');

    }

    public function deleteconnAction(){
        $supp_id = $this->params()->fromPost('supp_id');
        $fabric = $this->params()->fromPost('fabric');

        $this->getConnectionTable()->deleteConnection($supp_id, $fabric);
        return $this->redirect()->toRoute('suppliers', array('action' => 'connections'));
    }

    public function getSupplierTable(){
        if (!$this->supplierTable) {
            $sm = $this->getServiceLocator();
            $this->supplierTable = $sm->get('Pidzhak\Model\admin\SupplierTable');
        }
        return $this->supplierTable;
    }

    public function getConnectionTable(){
        if (!$this->connectionTable) {
            $sm = $this->getServiceLocator();
            $this->connectionTable = $sm->get('Pidzhak\Model\admin\ConnectionTable');
        }
        return $this->connectionTable;
    }
}