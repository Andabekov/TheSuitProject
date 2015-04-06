<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:41
 */

namespace Pidzhak\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array());
    }
}