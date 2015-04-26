<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 13.04.2015
 * Time: 11:46
 */


namespace Pidzhak\Controller\redactor;

use Pidzhak\Form\Redactor\UploadForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('pidzhak');
        }

        $view = new ViewModel(array('info' => $this->getServiceLocator()->get('AuthService')->getStorage()));
        $view->setTemplate('pidzhak/redactor/index.phtml');
        return $view;
    }

    public function compareAction(){
        $form = new UploadForm('upload-form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
//                var_dump("<br>");
                //var_dump($data);
                // Form is valid, save the form!
                //return $this->redirect()->toRoute('re');
            }
        }

        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('pidzhak/redactor/upload-form.phtml');
        return $view;
    }
}