<?php
namespace Pidzhak\Controller\seller;

use Pidzhak\Model\seller\ClotherMeasure;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\seller\BodyMeasure;
use Pidzhak\Form\seller\MeasureForm;

class MeasureController extends AbstractActionController
{
    protected $bodyMeasureTable;
    protected $clotherMeasureTable;
    protected $customerTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'bodymeasures' => $this->getBodyMeasureTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new MeasureForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $clothermeasure = new ClotherMeasure();
            $bodymeasure = new BodyMeasure();
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $bodymeasure->setPostfix('_1');
                $bodymeasure->exchangeArray($form->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_2');
                $bodymeasure->exchangeArray($form->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_3');
                $bodymeasure->exchangeArray($form->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_4');
                $bodymeasure->exchangeArray($form->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_5');
                $bodymeasure->exchangeArray($form->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);



                $clothermeasure->setPostfix('_1');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                $clothermeasure->setPostfix('_2');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                $clothermeasure->setPostfix('_3');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                return $this->redirect()->toRoute('measure');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $customer_id = (int) $this->params()->fromRoute('id', 0);
        if (!$customer_id) {
            return $this->redirect()->toRoute('measure', array(
                'action' => 'add'
            ));
        }

        $form  = new MeasureForm();
        $form->get('submit')->setAttribute('value', 'Edit');

        try {
            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '1');
            $bodymeasure->setPostfix('_1');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '2');
            $bodymeasure->setPostfix('_2');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '3');
            $bodymeasure->setPostfix('_3');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '4');
            $bodymeasure->setPostfix('_4');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '5');
            $bodymeasure->setPostfix('_5');
            $form->bind($bodymeasure);


            $clothermeasure = $this->getClotherMeasureTable()->getClotherMeasureByCustomerAndClother($customer_id, '1');
            $clothermeasure->setPostfix('_1');
            $clothermeasure->setPrefix('c_');
            $form->bind($clothermeasure);

            $clothermeasure = $this->getClotherMeasureTable()->getClotherMeasureByCustomerAndClother($customer_id, '2');
            $clothermeasure->setPostfix('_2');
            $clothermeasure->setPrefix('c_');
            $form->bind($clothermeasure);

        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('measure', array(
                'action' => 'index'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $clothermeasure = new ClotherMeasure();
            $bodymeasure = new BodyMeasure();
            $form_edited = new MeasureForm();
            $form_edited->setData($request->getPost());
            if ($form_edited->isValid()) {

                $bodymeasure->setPostfix('_1');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_2');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_3');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_4');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_5');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $clothermeasure->setPostfix('_1');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form_edited->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                $clothermeasure->setPostfix('_2');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form_edited->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                return $this->redirect()->toRoute('measure');
            }
        }


        return array(
            'id' => $customer_id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('measure');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getBodyMeasureTable()->deleteBodyMeasure($id);
            }

            return $this->redirect()->toRoute('measure');
        }

        return array(
            'id'    => $id,
            'measure' => $this->getBodyMeasureTable()->getBodyMeasure($id)
        );
    }


    public function secondstepAction()
    {
        $customer_id = (int) $this->params()->fromRoute('id', 0);

        $form  = new MeasureForm();
        $form->get('submit')->setAttribute('value', 'Сохранить и продолжить');
        $form->get('customer_id_1') ->setValue($customer_id);
        $form->get('customer_id_2') ->setValue($customer_id);
        $form->get('customer_id_3') ->setValue($customer_id);
        $form->get('customer_id_4') ->setValue($customer_id);
        $form->get('customer_id_5') ->setValue($customer_id);
        $form->get('c_customer_id_1') ->setValue($customer_id);
        $form->get('c_customer_id_2') ->setValue($customer_id);

        try {
            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '1');
            $bodymeasure->setPostfix('_1');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '2');
            $bodymeasure->setPostfix('_2');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '3');
            $bodymeasure->setPostfix('_3');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '4');
            $bodymeasure->setPostfix('_4');
            $form->bind($bodymeasure);

            $bodymeasure = $this->getBodyMeasureTable()->getBodyMeasureByCustomerAndClother($customer_id, '5');
            $bodymeasure->setPostfix('_5');
            $form->bind($bodymeasure);


            $clothermeasure = $this->getClotherMeasureTable()->getClotherMeasureByCustomerAndClother($customer_id, '1');
            $clothermeasure->setPostfix('_1');
            $clothermeasure->setPrefix('c_');
            $form->bind($clothermeasure);

            $clothermeasure = $this->getClotherMeasureTable()->getClotherMeasureByCustomerAndClother($customer_id, '2');
            $clothermeasure->setPostfix('_2');
            $clothermeasure->setPrefix('c_');
            $form->bind($clothermeasure);

        }
        catch (\Exception $ex) {

        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $clothermeasure = new ClotherMeasure();
            $bodymeasure = new BodyMeasure();
            $form_edited = new MeasureForm();
            $form_edited->setData($request->getPost());
            if ($form_edited->isValid()) {

                $bodymeasure->setPostfix('_1');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_2');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_3');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_4');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $bodymeasure->setPostfix('_5');
                $bodymeasure->exchangeArray($form_edited->getData());
                $this->getBodyMeasureTable()->saveBodyMeasure($bodymeasure);

                $clothermeasure->setPostfix('_1');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form_edited->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                $clothermeasure->setPostfix('_2');
                $clothermeasure->setPrefix('c_');
                $clothermeasure->exchangeArray($form_edited->getData());
                $this->getClotherMeasureTable()->saveClotherMeasure($clothermeasure);

                $idval = $request->getPost()->get('customer_id_1');
                return $this->redirect()->toRoute('order'
                    , array(
                        'action' => 'thirdstep',
                        'id' => $idval,
                        'measureTypeSelect' => $bodymeasure->measureTypeSelect
                    ));
            }else {
                $form->highlightErrorElements();
            }
        }



        if ($customer_id == 0) {
            $customer_id = $form->get('customer_id_1')->getValue();
        } else {
            $customer = $this->getCustomerTable()->getCustomer($customer_id);
        }


        $view = new ViewModel(array(
                'id' => $customer_id,
                'customer' => $customer,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/measure/second.phtml');
        return $view;

    }



    /*Inversion of Control*/
    public function getBodyMeasureTable()
    {
        if (!$this->bodyMeasureTable) {
            $sm = $this->getServiceLocator();
            $this->bodyMeasureTable = $sm->get('Pidzhak\Model\seller\BodyMeasureTable');
        }
        return $this->bodyMeasureTable;
    }
    public function getClotherMeasureTable()
    {
        if (!$this->clotherMeasureTable) {
            $sm = $this->getServiceLocator();
            $this->clotherMeasureTable = $sm->get('Pidzhak\Model\seller\ClotherMeasureTable');
        }
        return $this->clotherMeasureTable;
    }

    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\seller\CustomerTable');
        }
        return $this->customerTable;
    }
}