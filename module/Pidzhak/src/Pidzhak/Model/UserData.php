<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 08.04.2015
 * Time: 20:35
 */

namespace Pidzhak\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class UserData
{
    public $username;
    public $password;
    public $access_type_id;

    public $name;
    public $surname;
    public $email;
    public $phone;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->username         = (isset($data['username'])) ? $data['username'] : null;
        $this->password         = (isset($data['password'])) ? $data['password'] : null;
        $this->access_type_id   = (isset($data['access_type_id'])) ? $data['access_type_id'] : null;

        $this->name             = (isset($data['name'])) ? $data['name'] : null;
        $this->surname          = (isset($data['surname'])) ? $data['surname'] : null;
        $this->email            = (isset($data['email'])) ? $data['email'] : null;
        $this->phone            = (isset($data['phone'])) ? $data['phone'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function getUsername() {
        return $this->username;
    }
    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }
     public function setPassword($password) {
        $this->password = $password;
    }

    public function getAccessTypeId() {
        return $this->access_type_id;
    }
    public function setAccessTypeId($access_type_id) {
        $this->access_type_id = $access_type_id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }
    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPhone() {
        return $this->phone;
    }
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'password',
                'required' => true,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}