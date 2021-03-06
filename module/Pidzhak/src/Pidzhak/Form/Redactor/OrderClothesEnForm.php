<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 08/05/2015
 * Time: 11:25
 */

namespace Pidzhak\Form\redactor;

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
                'required' => 'required',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Item',
                'empty_option' => 'Choose item',
                'value_options' => $this->getProductsForSelect(),
            ),
        ));

        $this->add(array(
            'name' => 'measurement_type',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Measurement type',
                'value_options' => array(
                    '1' => 'По фигуре',
                    '2' => 'По изделию',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'fabric_id',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'readonly' => 'readonly'
            ),
            'options' => array(
                'label' => 'Fabric number',
            ),
        ));

        $this->add(array(
            'name' => 'brand_label',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Brand label',
                'empty_option' => 'Please choose',
                'value_options' => array(
                    '1' => 'Yes',
                    '2' => 'No',
                ),
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
                'readonly'=> 'readonly'
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
                'readonly'=> 'readonly'
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
                'readonly'=> 'readonly'
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
                'readonly'=> 'readonly'
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
                'readonly'=> 'readonly'
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
                'readonly'=> 'readonly'
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
                'label' => 'Заметки редактора',
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