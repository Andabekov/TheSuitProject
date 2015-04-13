<?php
namespace Pidzhak\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\Customer;
use Pidzhak\Form\CustomerForm;

class CustomerController extends AbstractActionController
{
    protected $customerTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'customers' => $this->getCustomerTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new CustomerForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $customer = new Customer();
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $customer->exchangeArray($form->getData());
                $this->getCustomerTable()->saveCustomer($customer);

                return $this->redirect()->toRoute('customer');
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
            return $this->redirect()->toRoute('customer', array(
                'action' => 'add'
            ));
        }

        try {
            $customer = $this->getCustomerTable()->getCustomer($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('customer', array(
                'action' => 'index'
            ));
        }

        $form  = new CustomerForm();
        $form->bind($customer);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCustomerTable()->saveCustomer($customer);

                return $this->redirect()->toRoute('customer');
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
            return $this->redirect()->toRoute('customer');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCustomerTable()->deleteCustomer($id);
            }

            return $this->redirect()->toRoute('customer');
        }

        return array(
            'id'    => $id,
            'customer' => $this->getCustomerTable()->getCustomer($id)
        );
    }

    /*Inversion of Control*/
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}