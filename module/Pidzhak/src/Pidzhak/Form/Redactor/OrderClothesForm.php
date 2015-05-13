<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 02/05/2015
 * Time: 09:29
 */

namespace Pidzhak\Form\Redactor;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class OrderClothesForm extends Form
{
    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter =$dbAdapter;

        parent::__construct('orderclothes');

        $this->add(array(
            'name' => 'c_id',
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
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Наименование изделия',
                'empty_option' => 'Выберите изделия',
                'value_options' => $this->getProductsForSelect(),
            ),
        ));

        $this->add(array(
            'name' => 'cycle_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Номер цикла',
                'empty_option' => 'Выберите цикл',
                'value_options' => $this->getCycleForSelect(),
            ),
        ));

        $this->add(array(
            'name' => 'cycle_date',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Дата прибытие по циклу',
                'empty_option' => 'Выберите цикл',
                'value_options' => $this->getCycleDates(),
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
            ),
        ));

        $this->add(array(
            'name' => 'textile_id',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Номер ткани',
                'empty_option' => 'Выберите материал',
                'value_options' => $this->getTextileForSelect(),
            ),
        ));
        $this->add(array(
            'name' => 'fabric_class',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Класс Ткани',
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
            ),
        ));

        $this->add(array(
            'name' => 'paytype_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Способ оплаты',
            ),
        ));

        $this->add(array(
            'name' => 'typeof_measure',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Вид замера',
            ),
        ));

        $this->add(array(
            'name' => 'label_brand',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Этикетка бранда',
            ),
        ));
        $this->add(array(
            'name' => 'style_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Номер стиля',
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_location',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '1. Монограм (расположение)',
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '1. Монограм (шрифт)',
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_font_color',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '1. Монограм (цвет шрифта)',
            ),
        ));
        $this->add(array(
            'name' => 'first_monogram_caption',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '1. Монограм (Надпись)',
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_location',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '2. Монограм (расположение)',
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '2. Монограм (шрифт)',
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_font_color',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '2. Монограм (цвет шрифта)',
            ),
        ));
        $this->add(array(
            'name' => 'second_monogram_caption',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => '2. Монограм (Надпись)',
            ),
        ));
        $this->add(array(
            'name' => 'seller_comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Комментарии Продавца',
            ),
        ));

        $this->add(array(
            'name' => 'orderclothessubmit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'orderclothessubmit',
                'class' => 'btn btn-primary'
            ),
        ));

        $this->add(array(
            'name' => 'orderclothescancel',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'orderclothescancel',
                'class' => 'btn btn-default'
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

    public function getProductsForSelect()
    {

        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id, clother FROM clothers ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['clother'];
        }
        return $selectData;
    }

    public function getCycleForSelect()
    {

        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id FROM cyclestable where CURDATE()<=order_accept_finish_date ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['id'];
        }
        return $selectData;
    }

    public function getTextileForSelect()
    {

        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id FROM fabricstable ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['id'];
        }
        return $selectData;
    }

    public function getCycleDates(){
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id, arrive_deadline_date FROM cyclestable where CURDATE()<=order_accept_finish_date ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['arrive_deadline_date'];
        }
        return $selectData;
    }
}