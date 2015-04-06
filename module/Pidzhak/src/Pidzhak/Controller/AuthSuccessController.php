<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 06.04.2015
 * Time: 15:07
 */


namespace Pidzhak\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthSuccessController extends AbstractActionController
{
    public function indexAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }
}