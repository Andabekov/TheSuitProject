<?php
namespace Pidzhak\Controller\seller;

use Pidzhak\GoogleContact\GoogleContactXmlParser;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\seller\Customer;
use Pidzhak\Form\seller\CustomerForm;

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
        return array('form' => $form, 'back' => '#',);
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
            'back' => '#',
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


    public function firststepAction()
    {
        $form = new CustomerForm();
        $form->get('submit')->setValue('Сохранить и продолжить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $customer = new Customer();
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            $idval = $request->getPost()->get('id');
            if($idval){
                return $this->redirect()->toRoute('measure'
                    , array(
                        'action' => 'secondstep',
                        'id' => $idval
                    ));
            }elseif ($form->isValid()) {

                $full_name=$request->getPost()->firstname.' '.$request->getPost()->lastname.' '.$request->getPost()->middlename;
                $mobile_phone=$request->getPost()->mobilephone;
                $access_token=$request->getPost()->access_token;

                try {
                    GoogleContactXmlParser::createContactFinal($full_name,$mobile_phone,$access_token);
                }
                catch (\Exception $ex) {
                }

                $customer->exchangeArray($form->getData());
                $this->getCustomerTable()->saveCustomer($customer);
                $idval = $this->getCustomerTable()->insertedCustomer();

//                return $this->redirect()->toRoute('measure'
//                    , array(
//                        'action' => 'secondstep',
//                        'id' => $idval,
//                    ));

                return $this->redirect()->toRoute('order'
                    , array(
                        'action' => 'thirdstep',
                        'id' => $idval,
                        'measureTypeSelect' => 1
                    ));

                //return $this->redirect()->toRoute('customer');
            }else {
                $form->highlightErrorElements();
            }
        }
        $view = new ViewModel(array(
                'form' => $form,
                'back' => '#',
            )
        );
        $view->setTemplate('pidzhak/customer/first.phtml');
        return $view;
    }


    /*Inversion of Control*/
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\seller\CustomerTable');
        }
        return $this->customerTable;
    }
}