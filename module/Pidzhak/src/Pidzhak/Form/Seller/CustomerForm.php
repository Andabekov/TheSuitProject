<?php
namespace Pidzhak\Form\Seller;

use Zend\Form\Form;
use  Zend\Form\Element\Hidden;

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
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Имя',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'lastname',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Фамилия',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'middlename',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Отчество',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'birthday',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дата рождения',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'mobilephone',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'mobilephone'
            ),
            'options' => array(
                'label' => 'Мобильный телефон',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Емайл',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'country',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Страна',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'city',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Город',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Адресс проживания',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'homephone',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Домашний телефон',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'work',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Место работы',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'position',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Должность',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'workaddress',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Адрес работы',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'workphone',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Рабочий телефон',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary'
            ),
        ));
    }

    public function highlightErrorElements()
    {
        foreach ($this->getElements() as $element) {
            if ($element->getMessages()) {
                $element->setAttribute('style', 'border-color:#a94442; box-shadow:inset 0 1px 1px rgba(0,0,0,.075);');
                $element->setLabelAttributes(array(
                    'class' => 'control-label col-xs-2',
                    'style' => 'color:#a94442'));
            }
        }
    }
}