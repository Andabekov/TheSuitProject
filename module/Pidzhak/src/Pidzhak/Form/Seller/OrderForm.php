<?php
namespace Pidzhak\Form\seller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;
use  Zend\Form\Element\Hidden;

class OrderForm extends Form
{
    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter =$dbAdapter;

        parent::__construct('order');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'customer_id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'dateofsale',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дата продажи',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_costumes',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество костюмов',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_shirts',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество сорочек',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_vests',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество жилеток',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_coats',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество пальто',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_trousers',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество брюк',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_shirts',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество сорочек',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_jacket',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество пиджаков',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_butterflies',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество бабочек',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'number_of_sash',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Количество кушаков',
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'cityofsale_id',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Город продажи',
//                'label_attributes' => array('class' => 'control-label col-xs-2'),
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getTableForSelect('citiestable', 'id', 'city_name'),
            ),
        ));
        $this->add(array(
            'name' => 'pointofsale',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Место продажи',
                'empty_option' => 'Не выбрано',
                'value_options' => array(
                    'Офис'=>'Офис',
                    'Выездной сервис'=>'Выездной сервис'
                )
//                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));

        $this->add(array(
            'name' => 'seller_id',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Продавец',
//                'label_attributes' => array('class' => 'control-label col-xs-2'),
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getSellersForSelect(),
            )
        ));


        $this->add(array(
            'name' => 'ordersubmit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'ordersubmit',
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

    public function getSellersForSelect()
    {

        $dbAdapter = $this->adapter;
        $sql       = 'SELECT  id, name,surname FROM userstable WHERE access_type_id = 1 ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['name'].$res['surname'];
        }
        return $selectData;
    }
    public function getTableForSelect($table, $idcolumn, $valuecolumn )
    {

        $dbAdapter = $this->adapter;
        $sql       = 'SELECT  '.$idcolumn.', '.$valuecolumn.' FROM '.$table.'';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res[$idcolumn]] = $res[$valuecolumn];
        }
        return $selectData;
    }
}