<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:57
 */

namespace Pidzhak\Form\admin;

use Zend\Form\Form;

class FabricForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('fabric');

        $this->add(array(
            'name' => 'id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Номер ткани',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'fabric_class',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Класс ткани',
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