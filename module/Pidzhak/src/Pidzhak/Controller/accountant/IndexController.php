<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:52
 */

namespace Pidzhak\Controller\accountant;

use Pidzhak\Form\seller\FinanceOperationsForm;
use Pidzhak\Model\accountant\Task;
use Pidzhak\Model\seller\FinanceOperations;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $financeOperationsTable;
    protected $certificateTable;
    protected $orderclothesTable;
    protected $requestTable;
    protected $taskTable;

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

            if($request->getPost()['oper_type']=='Штрафы/Бонусы'){
                $oper = new FinanceOperations();
                $oper->oper_type=$request->getPost()['oper_type'];
                $oper->oper_cost=0;
                $oper->oper_date=$request->getPost()['oper_date'];
                $oper->oper_status=0;
                $oper->oper_comment='Штраф <br>Тип штрафа: '.$request->getPost()['penalty_type'].'<br>Продавец: '.$request->getPost()['seller_id']."<br>Комментарии: ".$request->getPost()['penalty_name'];

                $this->getFinanceOperationsTable()->saveFinanceOperations($oper);

                return $this->redirect()->toRoute('accountant');
            } else{
                if ($form->isValid()) {
                    $finance->exchangeArray($form->getData());
                    $this->getFinanceOperationsTable()->saveFinanceOperations($finance);

                    return $this->redirect()->toRoute('accountant');
                }else {
                    $form->highlightErrorElements();
                }
            }
        }

        $sellers = $this->getOrderClothesTable()->getSellersList();
        $penalties = $this->getOrderClothesTable()->getPenaltiesList();

        $view = new ViewModel(array(
            'form' => $form,
            'sellers' => $sellers,
            'penalties' => $penalties,
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

    public function requestsAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/accountant/requests.phtml');
        return $view;
    }

    public function requeststatusAction(){
        $id = $this->params()->fromPost('id');

        if($id!=''){
            $this->getRequestTable()->requestStatus($id);
        }

        return $this->redirect()->toRoute('accountant');
    }

    public function tasksAction(){
        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/accountant/tasks.phtml');
        return $view;
    }

    public function addtaskAction(){

        $task_type = $this->params()->fromPost('task_type');
        $task_due_date = $this->params()->fromPost('task_due_date');
        $task_body = $this->params()->fromPost('task_body');

        if($task_type!='' && $task_due_date!='' && $task_body!=''){
            $task = new Task();
            $task->task_type = $task_type;
            $task->task_due_date = $task_due_date;
            $task->task_body = $task_body;
            $task->task_given_date = date('Y-m-d');
            $task->task_status = 'Не выполнен';

            $this->getTaskTable()->saveTask($task);
        }

        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/accountant/addTask.phtml');
        return $view;
    }

    public function taskstartedAction(){
        $id = $this->params()->fromPost('id');

        if($id){
            $this->getTaskTable()->setTaskStarted($id);
        }

        return $this->redirect()->toRoute('accountant', array('action' => 'tasks'));
    }

    public function taskfinishedAction(){
        $id = $this->params()->fromPost('id');

        if($id){
            $this->getTaskTable()->setTaskFinished($id);
        }

        return $this->redirect()->toRoute('accountant', array('action' => 'tasks'));
    }

    public function getFinanceOperationsTable()
    {
        if (!$this->financeOperationsTable) {
            $sm = $this->getServiceLocator();
            $this->financeOperationsTable = $sm->get('Pidzhak\Model\seller\FinanceOperationsTable');
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

    public function getOrderClothesTable()
    {
        if (!$this->orderclothesTable) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTable = $sm->get('Pidzhak\Model\seller\OrderClothesTable');
        }
        return $this->orderclothesTable;
    }

    public function getRequestTable(){
        if (!$this->requestTable) {
            $sm = $this->getServiceLocator();
            $this->requestTable = $sm->get('Pidzhak\Model\accountant\RequestTable');
        }
        return $this->requestTable;
    }

    public function getTaskTable(){
        if (!$this->taskTable) {
            $sm = $this->getServiceLocator();
            $this->taskTable = $sm->get('Pidzhak\Model\accountant\TaskTable');
        }
        return $this->taskTable;
    }
}