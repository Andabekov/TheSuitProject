<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 03/05/2015
 * Time: 18:10
 */

namespace Pidzhak\Form\redactor;

use Zend\Form\Form;

class SystemCodeForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('systemcode');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'system_code',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Системный код',
            ),
        ));
        $this->add(array(
            'name' => 'fabric_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Номер ткани',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Описание',
            ),
        ));
    }
}