<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 17:09
 */

namespace Pidzhak\Form\seller;

use Zend\Form\Form;

class FinanceOperationsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('accounting');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'oper_comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Комментарии',
            ),
        ));

        $this->add(array(
            'name' => 'oper_cost',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Сумма операции',
            ),
        ));

        $this->add(array(
            'name' => 'oper_type',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Тип операции',
                'empty_option' => 'Не выбрано',
                'value_options' => array(
                    'Поступление' => 'Поступление',
                    'Затраты' => 'Затраты',
                    'Перевод (из кассы)' => 'Перевод (из кассы)',
                    'Перевод (на кассу)' => 'Перевод (на кассу)',
                    'Клиент заплатил карточкой' => 'Клиент заплатил карточкой',
                    'Клиент должен денег' => 'Клиент должен денег',
                    'Клиент взял рассрочку' => 'Клиент взял рассрочку',
                    'Клиент вернул долг' => 'Клиент вернул долг',
                    'Клиент заплатил сертификатом' => 'Клиент заплатил сертификатом',
                    'Штрафы/Бонусы' => 'Штрафы/Бонусы',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Добавить',
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