<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 19:03
 */

namespace Pidzhak\Form\admin;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class ConnectionForm extends Form
{

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter =$dbAdapter;

        parent::__construct('connection');

        $this->add(array(
            'name' => 'supplier_id',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Поставщик',
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getSupplierList(),
                'label_attributes' => array('class' => 'control-label col-xs-2')
            ),
        ));
        $this->add(array(
            'name' => 'fabric_class',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Класс ткани',
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getFabricList(),
                'label_attributes' => array('class' => 'control-label col-xs-2')
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

    public function getSupplierList()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id, supplier_name FROM fabric_suppliers ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['supplier_name'];
        }
        return $selectData;
    }

    public function getFabricList()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT DISTINCT(fabric_class) FROM fabricstable';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['fabric_class']] = $res['fabric_class'];
        }
        return $selectData;
    }
}