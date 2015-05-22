<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 15/04/2015
 * Time: 14:01
 */

namespace Pidzhak\Form\admin;

use Zend\Form\Form;
use  Zend\Form\Element\Hidden;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('user');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'username',
            'required' => true,
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Логин',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'type' => 'password',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'true'
            ),
            'options' => array(
                'label' => 'Пароль',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'access_type_id',
            'type' => 'select',
            'required' => true,
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Роль',
                'value_options' => array(
                    '1' => 'Продавец',
                    '2' => 'Редактор',
                    '3' => 'Бухгалтер',
                    '4' => 'Директор',
                    '5' => 'Курьер',
                    '6' => 'Админ',
                ),
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'required' => true,
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Имя',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'surname',
            'required' => true,
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
            'name' => 'email',
            'required' => true,
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
            'name' => 'phone',
            'required' => true,
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Телефон',
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