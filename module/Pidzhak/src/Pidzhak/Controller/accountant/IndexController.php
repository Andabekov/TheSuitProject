<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:52
 */

namespace Pidzhak\Controller\accountant;

use Pidzhak\Form\Seller\FinanceOperationsForm;
use Pidzhak\Model\Seller\FinanceOperations;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $financeOperationsTable;
    protected $certificateTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/accountant/index.phtml');
        return $view;
    }

    public function certAction(){
        $view = new ViewModel();
        $view->setTemplate('pidzhak/accountant/certificates.phtml');
        return $view;
    }

    public function certstatusAction(){
        $request = $this->getRequest();

        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getCertificateTable()->changeStatus($id);
        }

        return $this->redirect()->toRoute('accountant', array('action' => 'cert'));
    }

    public function confirmAction(){
        $request = $this->getRequest();

        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getFinanceOperationsTable()->confirm($id);
        }

        return $this->redirect()->toRoute('accountant');
    }

    public function addAction(){

        $form = new FinanceOperationsForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $finance = new FinanceOperations();
            $form->setInputFilter($finance->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $finance->exchangeArray($form->getData());
                $this->getFinanceOperationsTable()->saveFinanceOperations($finance);

                return $this->redirect()->toRoute('accountant');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('pidzhak/accountant/addFinanceOper.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getFinanceOperationsTable()->deleteFinanceOperations($id);
        }

        return $this->redirect()->toRoute('accountant');
    }

    public function getFinanceOperationsTable()
    {
        if (!$this->financeOperationsTable) {
            $sm = $this->getServiceLocator();
            $this->financeOperationsTable = $sm->get('Pidzhak\Model\Seller\FinanceOperationsTable');
        }
        return $this->financeOperationsTable;
    }

    public function getCertificateTable()
    {
        if (!$this->certificateTable) {
            $sm = $this->getServiceLocator();
            $this->certificateTable = $sm->get('Pidzhak\Model\accountant\CertificateTable');
        }
        return $this->certificateTable;
    }
}