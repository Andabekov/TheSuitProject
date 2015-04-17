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
        if(isset($data['access_type_id'])){
            if(isset($this->id)){
                    switch($data['access_type_id']){
                        case 1: $this->access_type_id='Продавец'; break;
                        case 2: $this->access_type_id='Редактор'; break;
                        case 3: $this->access_type_id='Бухгалтер'; break;
                        case 4: $this->access_type_id='Директор'; break;
                        case 5: $this->access_type_id='Курьер'; break;
                        case 6: $this->access_type_id='Админ'; break;
                    }
            } else {
                $this->access_type_id   = (isset($data['access_type_id'])) ? $data['access_type_id'] : null;
            }
        }
        else {
            $this->access_type_id=null;
        }

        $this->id               = (isset($data['id'])) ? $data['id'] : null;
        $this->username         = (isset($data['username'])) ? $data['username'] : null;
        $this->password         = (isset($data['password'])) ? $data['password'] : null;

        $this->name             = (isset($data['name'])) ? $data['name'] : null;
        $this->surname          = (isset($data['surname'])) ? $data['surname'] : null;
        $this->email            = (isset($data['email'])) ? $data['email'] : null;
        $this->phone            = (isset($data['phone'])) ? $data['phone'] : null;
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
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'surname',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}