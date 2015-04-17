<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:56
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\FabricForm;
use Pidzhak\Model\admin\Fabric;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FabricController extends AbstractActionController
{
    protected $fabricTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'fabric' => $this->getFabricTable()->fetchAll(),
            )
        );
        $view->setTemplate('pidzhak/admin/fabrics.phtml');
        return $view;
    }

    public function addAction()
    {
        $form = new FabricForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $fabric = new Fabric();
            $form->setInputFilter($fabric->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $fabric->exchangeArray($form->getData());
                $this->getFabricTable()->saveFabric($fabric);

                return $this->redirect()->toRoute('fabrics');
            }else {
                $form->highlightErrorElements();
            }
        }

        $view = new ViewModel(array('form' => $form,));
        $view->setTemplate('pidzhak/admin/addFabric.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('fabrics', array('action' => 'add'));
        }

        try {
            $fabric = $this->getFabricTable()->getFabric($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('fabrics', array('action' => 'index'));
        }

        $form  = new FabricForm();
        $form->bind($fabric);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($fabric->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getFabricTable()->saveFabric($fabric);
                return $this->redirect()->toRoute('fabrics');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editFabric.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('fabrics');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $this->getFabricTable()->deleteFabric($id);
            return $this->redirect()->toRoute('fabrics');
        }

        return array(
            'id'    => $id,
            'fabric' => $this->getFabricTable()->getFabric($id)
        );
    }

    public function getFabricTable(){
        if (!$this->fabricTable) {
            $sm = $this->getServiceLocator();
            $this->fabricTable = $sm->get('Pidzhak\Model\admin\FabricTable');
        }
        return $this->fabricTable;
    }
}