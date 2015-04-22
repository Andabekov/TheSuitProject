<?php
namespace Pidzhak\Controller\Seller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\Seller\Order;
use Pidzhak\Form\Seller\OrderForm;
use Pidzhak\Form\Seller\OrderClothesForm;

class OrderController extends AbstractActionController
{
    protected $orderTable;
    protected $customerTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'orders' => $this->getOrderTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new OrderForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $order = new Order();
            $form->setInputFilter($order->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $order->exchangeArray($form->getData());
                $this->getOrderTable()->saveOrder($order);

                return $this->redirect()->toRoute('order');
            } else {
                $form->highlightErrorElements();
                // other error logic
            }
        }
        return array('form' => $form);
    }


    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('order', array(
                'action' => 'add'
            ));
        }

        try {
            $order = $this->getOrderTable()->getOrder($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('order', array(
                'action' => 'index'
            ));
        }

        $form = new OrderForm();
        $form->bind($order);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($order->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getOrderTable()->saveOrder($order);

                //return $this->redirect()->toRoute('order');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('order');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->getOrderTable()->deleteOrder($id);
            }

            return $this->redirect()->toRoute('order');
        }

        return array(
            'id' => $id,
            'order' => $this->getOrderTable()->getOrder($id)
        );
    }


    public function thirdstepAction()
    {
        $customer_id = (int)$this->params()->fromRoute('id', 0);


        $form = new OrderForm();
        $form->get('customer_id')->setValue($customer_id);
        $form->get('submit')->setValue('Add');


        $request = $this->getRequest();
        if ($request->isPost()) {
            $order = new Order();
            $form->setInputFilter($order->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $order->exchangeArray($form->getData());
                $this->getOrderTable()->saveOrder($order);

                return $this->redirect()->toRoute('order');
            } else {
                $form->highlightErrorElements();
                // other error logic
            }
        }

        if ($customer_id == 0) {
            $customer_id = $form->get('customer_id')->getValue();
        } else {
            $customer = $this->getCustomerTable()->getCustomer($customer_id);
        }


        $cform = new OrderClothesForm();
        $cform->get('submit')->setValue('Добавить');

        $view = new ViewModel(array(
                'id' => $customer_id,
                'form' => $form,
                'cform' => $cform,
                'customer' => $customer,
            )
        );
        $view->setTemplate('pidzhak/order/third.phtml');
        return $view;
    }


    /*Inversion of Control*/
    public function getOrderTable()
    {
        if (!$this->orderTable) {
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Pidzhak\Model\Seller\OrderTable');
        }
        return $this->orderTable;
    }

    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\Seller\CustomerTable');
        }
        return $this->customerTable;
    }
}