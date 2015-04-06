<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 06.04.2015
 * Time: 15:04
 */


namespace Pidzhak\Model;

use Zend\Authentication\Storage;

class AuthStorage extends Storage\Session
{
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}