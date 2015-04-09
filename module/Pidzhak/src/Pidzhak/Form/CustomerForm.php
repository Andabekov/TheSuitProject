<?php
namespace Pidzhak\Form;

use Zend\Form\Form;

class CustomerForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('customer');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'firstname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Имя',
            ),
        ));
        $this->add(array(
            'name' => 'lastname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Фамилия',
            ),
        ));
        $this->add(array(
            'name' => 'middlename',
            'type' => 'Text',
            'options' => array(
                'label' => 'Отчество',
            ),
        ));
        $this->add(array(
            'name' => 'birthday',
            'type' => 'Text',
            'options' => array(
                'label' => 'Дата рождения',
            ),
        ));
        $this->add(array(
            'name' => 'mobilephone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Мобильный телефон',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Емайл',
            ),
        ));
        $this->add(array(
            'name' => 'country',
            'type' => 'Text',
            'options' => array(
                'label' => 'Страна',
            ),
        ));
        $this->add(array(
            'name' => 'city',
            'type' => 'Text',
            'options' => array(
                'label' => 'Город',
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => 'Адресс проживания',
            ),
        ));
        $this->add(array(
            'name' => 'homephone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Домашний телефон',
            ),
        ));
        $this->add(array(
            'name' => 'work',
            'type' => 'Text',
            'options' => array(
                'label' => 'Место работы',
            ),
        ));
        $this->add(array(
            'name' => 'position',
            'type' => 'Text',
            'options' => array(
                'label' => 'Должность',
            ),
        ));
        $this->add(array(
            'name' => 'workaddress',
            'type' => 'Text',
            'options' => array(
                'label' => 'Адрес работы',
            ),
        ));
        $this->add(array(
            'name' => 'workphone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Рабочий телефон',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}