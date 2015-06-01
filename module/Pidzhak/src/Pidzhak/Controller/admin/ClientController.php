<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 16/04/2015
 * Time: 17:09
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\GoogleContact\GoogleContactXmlParser;
use Pidzhak\Model\Seller\Customer;
use Pidzhak\Form\Seller\CustomerForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ClientController extends AbstractActionController
{
    protected $customerTable;

    public function indexAction()
    {
        $view = new ViewModel(array(
                'customers' => $this->getCustomerTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/clients.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new CustomerForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $customer = new Customer();
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $full_name=$request->getPost()->firstname.' '.$request->getPost()->lastname.' '.$request->getPost()->middlename;
                $mobile_phone=$request->getPost()->mobilephone;
                $access_token=$request->getPost()->access_token;

                GoogleContactXmlParser::createContactFinal($full_name,$mobile_phone,$access_token);

//                var_dump(GoogleContactXmlParser::createContactFinal($full_name,$mobile_phone,$access_token));

                $customer->exchangeArray($form->getData());
                $this->getCustomerTable()->saveCustomer($customer);

                return $this->redirect()->toRoute('clients');
            }else {
                $form->highlightErrorElements();
                // other error logic
            }
        }
        $view = new ViewModel(array(
                'form' => $form,
                'back' => '/clients',
            )
        );
        $view->setTemplate('pidzhak/admin/addClient.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('clients', array(
                'action' => 'add'
            ));
        }

        try {
            $customer = $this->getCustomerTable()->getCustomer($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('clients', array(
                'action' => 'index'
            ));
        }

        $form  = new CustomerForm();
        $form->bind($customer);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCustomerTable()->saveCustomer($customer);

                return $this->redirect()->toRoute('clients');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'back' => '/clients',
            )
        );
        $view->setTemplate('pidzhak/admin/editClient.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('clients');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getCustomerTable()->deleteCustomer($id);

            return $this->redirect()->toRoute('clients');
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
            $this->customerTable = $sm->get('Pidzhak\Model\Seller\CustomerTable');
        }
        return $this->customerTable;
    }
}