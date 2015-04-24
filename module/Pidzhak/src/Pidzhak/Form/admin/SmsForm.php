<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 11:03
 */

namespace Pidzhak\Form\admin;

use Zend\Form\Form;

class SmsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('sms');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Текст сообщения',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'number',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'number'
            ),
            'options' => array(
                'label' => 'Номер получателя',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
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