<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 23/07/2015
 * Time: 13:51
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\TailorForm;
use Pidzhak\Model\admin\Tailor;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TailorController extends AbstractActionController
{
    protected $tailorTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'tailor' => $this->getTailorTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/tailors.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new TailorForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $tailor = new Tailor();
            $form->setInputFilter($tailor->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $tailor->exchangeArray($form->getData());
                $this->getTailorTable()->saveTailor($tailor);

                return $this->redirect()->toRoute('tailors');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/addTailor.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('tailors', array('action' => 'add'));
        }

        try {
            $tailor = $this->getTailorTable()->getTailor($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('tailors', array('action' => 'index'));
        }

        $form  = new TailorForm();
        $form->bind($tailor);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($tailor->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTailorTable()->saveTailor($tailor);
                return $this->redirect()->toRoute('tailors');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editTailor.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getTailorTable()->deleteTailor($id);

            return $this->redirect()->toRoute('admin');
        }

        return array(
            'id'    => $id,
            'tailor' => $this->getTailorTable()->getTailor($id)
        );
    }

    public function getTailorTable(){
        if (!$this->tailorTable) {
            $sm = $this->getServiceLocator();
            $this->tailorTable = $sm->get('Pidzhak\Model\admin\TailorTable');
        }
        return $this->tailorTable;
    }
}