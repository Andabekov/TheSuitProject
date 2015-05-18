<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:57
 */

namespace Pidzhak\Form\admin;

use Zend\Form\Form;

class StyleForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('style');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'style_num',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'true'
            ),
            'options' => array(
                'label' => 'Номер стиля',
            ),
        ));
        $this->add(array(
            'name' => 'cloth_type',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Тип изделия',
                'empty_option' => 'Не выбрано',
                'value_options' => array(
                    '1' => 'Пиджак',
                    '2' => 'Брюки',
                    '3' => 'Сорочка',
                    '4' => 'Жилетка',
                    '5' => 'Пальто',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'style_code',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Код стиля',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'style_code_fabric',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Код ткани стиля',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'style_code_desc',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Описание кода стиля',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Добавить стиль',
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