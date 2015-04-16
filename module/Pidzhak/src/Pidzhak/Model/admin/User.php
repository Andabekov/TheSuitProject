<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 08.04.2015
 * Time: 20:35
 */

namespace Pidzhak\Model\admin;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
    public $id;
    public $username;
    public $password;
    public $access_type_id;

    public $name;
    public $surname;
    public $email;
    public $phone;

    public function exchangeArray($data)
    {
        $this->id               = (isset($data['ID'])) ? $data['ID'] : null;
        $this->username         = (isset($data['USERNAME'])) ? $data['USERNAME'] : null;
        $this->password         = (isset($data['PASSWORD'])) ? $data['PASSWORD'] : null;
        $this->access_type_id   = (isset($data['ACCESS_TYPE_ID'])) ? $data['ACCESS_TYPE_ID'] : null;

        $this->name             = (isset($data['NAME'])) ? $data['NAME'] : null;
        $this->surname          = (isset($data['SURNAME'])) ? $data['SURNAME'] : null;
        $this->email            = (isset($data['EMAIL'])) ? $data['EMAIL'] : null;
        $this->phone            = (isset($data['PHONE'])) ? $data['PHONE'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public $inputFilter;

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}