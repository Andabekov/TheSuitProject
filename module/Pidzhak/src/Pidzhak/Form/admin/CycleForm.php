<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:58
 */

namespace Pidzhak\Form\admin;

use Zend\Form\Form;

class CycleForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('cycle');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'order_accept_start_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дата начала приема заказов',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'order_accept_finish_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дата окончания приема заказов',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'order_check_deadline_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дэдлайн сверки заказа',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'submit_deadline_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дэдлайн предзаказа',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'ship_deadline_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дэдлайн отправки заказа',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'arrive_deadline_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дэдлайн доставки заказа',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'cycle_active',
            'type' => 'Hidden',
            'value' => '1',
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