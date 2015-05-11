<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 08/05/2015
 * Time: 11:25
 */

namespace Pidzhak\Form\Redactor;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class OrderClothesEnForm extends Form
{
    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter =$dbAdapter;

        parent::__construct('orderclothes');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'order_cloth_id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'cloth_type',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
//                'readonly' => 'readonly'
            ),
            'options' => array(
                'label' => 'Item',
                'empty_option' => 'Choose item',
                'value_options' => $this->getProductsForSelect(),
            ),
        ));

        $this->add(array(
            'name' => 'measurement_type',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
//                'readonly' => 'readonly'
            ),
            'options' => array(
                'label' => 'Measurement type',
            ),
        ));

        $this->add(array(
            'name' => 'fabric_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
//                'readonly' => 'readonly'
            ),
            'options' => array(
                'label' => 'Fabric number',
            ),
        ));

        $this->add(array(
            'name' => 'brand_label',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Brand label',
            ),
        ));

        $this->add(array(
            'name' => 'monogram1_pos',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '1. Monogram (position)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram1_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '1. Monogram (font)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram1_color_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '1. Monogram (colour font)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram1_text',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '1. Monogram (Text)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram2_pos',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '2. Monogram (position)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram2_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '2. Monogram (font)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram2_color_font',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '2. Monogram (colour font)',
            ),
        ));

        $this->add(array(
            'name' => 'monogram2_text',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '2. Monogram (Text)',
            ),
        ));

        $this->add(array(
            'name' => 'redactor_comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Заметеки редактора',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Сохранить',
                'class' => 'btn btn-primary'
            ),
        ));
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
}