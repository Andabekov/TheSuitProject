<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 15/04/2015
 * Time: 12:12
 */#

namespace Pidzhak\Controller\admin;

use Pidzhak\Form\admin\UserForm;
use Pidzhak\GoogleContact\GoogleContactUtil;
use Pidzhak\Model\admin\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    protected $userTable;

    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array(
                'info' => $this->getServiceLocator()->get('AuthService')->getStorage(),
                'user' => $this->getUserTable()->fetchAll(),
            )
        );

        var_dump(GoogleContactUtil::saveGoogleContact());

//        $view->setTemplate('pidzhak/admin/index.phtml');
        $view->setTemplate('pidzhak/admin/oauth.phtml');
        return $view;
    }

    public function addAction()
    {

        $form = new UserForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $user = new User();
            $form->setInputFilter($user->getInputFilter());

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $user->exchangeArray($form->getData());
                $this->getUserTable()->saveUser($user);

                return $this->redirect()->toRoute('admin');
            }else {
                $form->highlightErrorElements();
                // other error logic
            }
        }
//        return array('form' => $form);

        $view = new ViewModel(array(
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/addUser.phtml');
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array(
                'action' => 'add'
            ));
        }

        try {
            $user = $this->getUserTable()->getUser($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('admin', array(
                'action' => 'index'
            ));
        }

        $form  = new UserForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Сохранить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUserTable()->saveUser($user);

                return $this->redirect()->toRoute('admin');
            }
        }

        $view = new ViewModel(array(
                'id' => $id,
                'form' => $form,
            )
        );
        $view->setTemplate('pidzhak/admin/editUser.phtml');
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
            $this->getUserTable()->deleteUser($id);

            return $this->redirect()->toRoute('admin');
        }

        return array(
            'id'    => $id,
            'user' => $this->getUserTable()->getUser($id)
        );
    }

    public function getUserTable(){
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Pidzhak\Model\admin\UserTable');
        }
        return $this->userTable;
    }

}