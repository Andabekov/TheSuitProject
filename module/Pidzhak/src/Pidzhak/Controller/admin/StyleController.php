<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:55
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\StyleForm;
use Pidzhak\Model\admin\Style;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StyleController extends AbstractActionController
{
    protected $styleTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'style' => $this->getStyleTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/styles.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new StyleForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $style = new Style();
            $form->setInputFilter($style->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $style->exchangeArray($form->getData());
                $this->getStyleTable()->saveStyle($style);

                return $this->redirect()->toRoute('styles');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/addStyle.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('styles', array('action' => 'add'));
        }

        try {
            $style = $this->getStyleTable()->getStyle($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('styles', array('action' => 'index'));
        }

        $form  = new StyleForm();
        $form->bind($style);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($style->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getStyleTable()->saveStyle($style);
                return $this->redirect()->toRoute('styles');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editStyle.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('styles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getStyleTable()->deleteStyle($id);
            return $this->redirect()->toRoute('styles');
        }

        return array(
            'id'    => $id,
            'style' => $this->getStyleTable()->getStyle($id)
        );
    }

    public function getStyleTable(){
        if (!$this->styleTable) {
            $sm = $this->getServiceLocator();
            $this->styleTable = $sm->get('Pidzhak\Model\admin\StyleTable');
        }
        return $this->styleTable;
    }
}