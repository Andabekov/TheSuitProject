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

    public function getRole()
    {
        if (isset($_SESSION['userstable'])) {
            foreach ($_SESSION['userstable'] as $val) {
                $storage = $val;
            }

            if (!empty($storage)) {
                switch($storage['access_type_id']){
                    case 1: return 'seller'; break;
                    case 2: return 'redactor'; break;
                    case 3: return 'accountant'; break;
                    case 4: return 'director'; break;
                    case 5: return 'delivery'; break;
                    case 6: return 'admin'; break;
                }
            }
        }
        return 'nobody';
    }
}