<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 05/05/2015
 * Time: 16:27
 */

namespace Pidzhak\Form\redactor;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class TestModelForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('testmodel');

        $this
            ->setInputFilter(new InputFilter())
        ;

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'systemcode',
            'options' => array(
                'count' => 1,
                'should_create_template' => true,
                'allow_add' => true,
                'template_placeholder' => '__mycount__',
                'target_element' => array(
                    'type' => 'Pidzhak\Form\redactor\SystemCodeForm',
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Сохранить',
                'class' => 'btn btn-primary'
            ),
        ));

        $this->setValidationGroup(array(
            'csrf',
            'systemcode' => array(
                'code',
                'description',
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