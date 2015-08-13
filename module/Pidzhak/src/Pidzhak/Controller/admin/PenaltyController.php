<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 11/08/2015
 * Time: 12:01
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\PenaltyForm;
use Pidzhak\Model\admin\Penalty;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PenaltyController extends AbstractActionController
{
    protected $penaltyTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'penalty' => $this->getPenaltyTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/penalties.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new PenaltyForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $penalty = new Penalty();
            $form->setInputFilter($penalty->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $penalty->exchangeArray($form->getData());
                $this->getPenaltyTable()->savePenalty($penalty);

                return $this->redirect()->toRoute('penalties');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/addPenalty.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('penalties', array('action' => 'add'));
        }

        try {
            $penalty = $this->getPenaltyTable()->getPenalty($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('penalties', array('action' => 'index'));
        }

        $form  = new PenaltyForm();
        $form->bind($penalty);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($penalty->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPenaltyTable()->savePenalty($penalty);
                return $this->redirect()->toRoute('penalties');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editPenalty.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('penalties');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getPenaltyTable()->deletePenalty($id);
            return $this->redirect()->toRoute('penalties');
        }

        return array(
            'id'    => $id,
            'penalty' => $this->getPenaltyTable()->getPenalty($id)
        );
    }

    public function getPenaltyTable(){
        if (!$this->penaltyTable) {
            $sm = $this->getServiceLocator();
            $this->penaltyTable = $sm->get('Pidzhak\Model\admin\PenaltyTable');
        }
        return $this->penaltyTable;
    }

}
