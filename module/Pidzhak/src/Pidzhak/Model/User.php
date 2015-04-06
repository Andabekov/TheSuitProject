<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 06.04.2015
 * Time: 14:59
 */

namespace Pidzhak\Model;

use Zend\Form\Annotation;


class User
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Логин: "})
     */
    public $username;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Пароль:"})
     */
    public $password;

//    /**
//     * @Annotation\Type("Zend\Form\Element\Checkbox")
//     * @Annotation\Options({"label":"Remember Me ?:"})
//     */
//    public $rememberme;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Войти"})
     */
    public $submit;
}
