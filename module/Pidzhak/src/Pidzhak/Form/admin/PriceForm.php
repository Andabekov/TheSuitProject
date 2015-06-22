<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 11:03
 */

namespace Pidzhak\Form\admin;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class PriceForm extends Form
{

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {

        $this->adapter =$dbAdapter;

        parent::__construct('prices');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'fabric_class',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Класс ткани',
                'label_attributes' => array('class' => 'control-label col-xs-2'),
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getFabricClass(),
            ),
        ));
        $this->add(array(
            'name' => 'cloth_type',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Тип изделия',
                'label_attributes' => array('class' => 'control-label col-xs-2'),
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getProductsForSelect(),
            ),
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'Number',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Прайс',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'profit',
            'type' => 'Number',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Прибыль',
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'max_discount',
            'type' => 'Number',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Макс скидка',
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

    public function getErrorAjax(){
        foreach ($this->getElements() as $element) {
            if ($element->getMessages()) {
//                return $element->getName();;

                return $element->getMessages();
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

    public function getFabricClass(){
        $dbAdapter = $this->adapter;
        $sql       = 'select DISTINCT(fabric_class) from fabricstable ORDER BY fabric_class ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['fabric_class']] = $res['fabric_class'];
        }
        return $selectData;
    }
}