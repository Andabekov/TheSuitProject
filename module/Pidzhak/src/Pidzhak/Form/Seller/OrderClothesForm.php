<?php
namespace Pidzhak\Form\Seller;

use Zend\Form\Form;
use  Zend\Form\Element\Hidden;

class OrderClothesForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('orderclothes');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'order_id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'pricelistnum',
            'type' => 'Hidden',
        ));


        $this->add(array(
            'name' => 'product_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Наименование изделия',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'cycle_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Номер цикла',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'cycle_date',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Дата прибытие по циклу',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'preferred_date',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Предпочтительная дата выдачи',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'textile_id',
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
            'name' => 'textile_class',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Класс Ткани',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'actual_amount',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Фактическая сумма оплаты в тенге*',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'paytype',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Способ оплаты',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'typeof_measure',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Вид замера',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'label_brand',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Этикетка бранда',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'style_number',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Номер стиля',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_location',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '1. Монограм (расположение)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '1. Монограм (шрифт)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_font_color',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '1. Монограм (цвет шрифта)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_caption',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '1. Монограм (Надпись)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_location',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '2. Монограм (расположение)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '2. Монограм (шрифт)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_font_color',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '2. Монограм (цвет шрифта)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_caption',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => '2. Монограм (Надпись)',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'seller_comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Комментарии Продавца',
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