<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:55
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\CycleForm;
use Pidzhak\Model\admin\Cycle;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CycleController extends AbstractActionController
{
    protected $cycleTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'cycle' => $this->getCycleTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/cycles.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new CycleForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $cycle = new Cycle();
            $form->setInputFilter($cycle->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $cycle->exchangeArray($form->getData());
                $this->getCycleTable()->saveCycle($cycle);

                return $this->redirect()->toRoute('cycles');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/addCycle.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cycles', array('action' => 'add'));
        }

        try {
            $cycle = $this->getCycleTable()->getCycle($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('cycles', array('action' => 'index'));
        }

        $form  = new CycleForm();
        $form->bind($cycle);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($cycle->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCycleTable()->saveCycle($cycle);
                return $this->redirect()->toRoute('cycles');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editCycle.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cycles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getCycleTable()->deleteCycle($id);
            return $this->redirect()->toRoute('cycles');
        }

        return array(
            'id'    => $id,
            'cycle' => $this->getCycleTable()->getCycle($id)
        );
    }

    public function getCycleTable(){
        if (!$this->cycleTable) {
            $sm = $this->getServiceLocator();
            $this->cycleTable = $sm->get('Pidzhak\Model\admin\CycleTable');
        }
        return $this->cycleTable;
    }
}