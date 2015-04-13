<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:41
 */

namespace Pidzhak\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use Pidzhak\Model\User;

class IndexController extends AbstractActionController
{

    protected $form;
    protected $storage;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()->get('Pidzhak\Model\AuthStorage');
        }
        return $this->storage;
    }

    public function getForm()
    {
        if (! $this->form) {
            $user       = new User();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }

        return $this->form;
    }

    public function indexAction()
    {

        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('seller');
        }

        return new ViewModel();
    }

    public function loginAction()
    {
        $accessTypeIdParam = (int) $this->params()->fromRoute('accessTypeId', 0);
        if(!$accessTypeIdParam){
            return $this->redirect()->toRoute('home');
        }

        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('seller');
        }

        $form       = $this->getForm();

        $form->get('access_type_id')->setValue($accessTypeIdParam);

        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        $form       = $this->getForm();

        $request = $this->getRequest();
        if ($request->isPost()){

//            fwrite(STDOUT, "test stop");

            $form->setData($request->getPost());
            if ($form->isValid()){
                //check authentication...
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('username'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {

                    $resultRow = $this->getAuthService()->getAdapter()->getResultRowObject();


                    if($request->getPost('access_type_id')!=$resultRow->ACCESS_TYPE_ID){
//                        $this->flashmessenger()->clearMessagesFromContainer();
                        $this->flashmessenger()->addMessage("Данный пользыватель не имеет доступа к выбранной подсистеме");
                        $this->getSessionStorage()->forgetMe();
                        $this->getAuthService()->clearIdentity();

                    } else {
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                        $this->getAuthService()->getStorage()->write(array(
                            'username'       => $resultRow->USERNAME,
                            'access_type_id' => $resultRow->ACCESS_TYPE_ID,
                            'name'           => $resultRow->NAME,
                            'surname'        => $resultRow->SURNAME,
                            'email'          => $resultRow->EMAIL,
                            'phone'          => $resultRow->PHONE,
                        ));

                        $route = '';

                        switch($resultRow->ACCESS_TYPE_ID){
                            case 1: $route='seller'; break;
                            case 2: $route='redactor'; break;
                            case 3: $route='accountant'; break;
                            case 4: $route='director'; break;
                            case 5: $route='deliver'; break;
                            case 6: $route='admin'; break;
                        }

                        return $this->redirect()->toRoute($route);
                    }
                }
            }
        }

        return $this->redirect()->toRoute('pidzhak', array('action'=>'login', 'accessTypeId'=>$request->getPost('access_type_id')));

    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

//        $this->flashmessenger()->addMessage("Вы вышли из системы");

        return $this->redirect()->toRoute('home');
    }
}