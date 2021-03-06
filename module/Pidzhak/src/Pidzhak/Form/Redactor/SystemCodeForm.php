<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 03/05/2015
 * Time: 18:10
 */

namespace Pidzhak\Form\redactor;

use Pidzhak\Model\redactor\SystemCode;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class SystemCodeForm extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('systemcode');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new SystemCode())
        ;

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'order_cloth_id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'code',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'true',
                'style' => 'width: 150px'
            ),
            'options' => array(
                'label' => 'Системный код',
            ),
        ));
        $this->add(array(
            'name' => 'fabric_optional',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'style' => 'width: 150px'
            ),
            'options' => array(
                'label' => 'Номер ткани',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'true',
                'style' => 'width: 360px'
            ),
            'options' => array(
                'label' => 'Описание',
            ),
        ));
        $this->add(array(
            'name' => 'deleteBtn',
            'type' => 'Button',
            'attributes' => array(
                'class' => 'glyphicon glyphicon-trash',
                'onclick' => 'deleteRowClick($(this))'
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'code' => array(
                'required' => true,
            ),
            'description' => array(
                'required' => true,
            ),
        );
    }
}