<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 06/05/2015
 * Time: 17:01
 */

namespace Pidzhak\Model\redactor;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class OrderClothes implements InputFilterAwareInterface
{
    public $id;
    public $order_cloth_id;
    public $cloth_type;
    public $measurement_type;
    public $fabric_id;
    public $brand_label;

    public $monogram1_pos;
    public $monogram1_font;
    public $monogram1_color_font;
    public $monogram1_text;

    public $monogram2_pos;
    public $monogram2_font;
    public $monogram2_color_font;
    public $monogram2_text;

    public $redactor_comment;

    public function exchangeArray($data)
    {
        $this->id               = (isset($data['id'])) ? $data['id'] : null;
        $this->order_cloth_id   = (isset($data['order_cloth_id'])) ? $data['order_cloth_id'] : null;
        $this->cloth_type       = (isset($data['cloth_type'])) ? $data['cloth_type'] : null;
        $this->measurement_type = (isset($data['measurement_type'])) ? $data['measurement_type'] : null;
        $this->fabric_id        = (isset($data['fabric_id'])) ? $data['fabric_id'] : null;
        $this->brand_label      = (isset($data['brand_label'])) ? $data['brand_label'] : null;

        $this->monogram1_pos        = (isset($data['monogram1_pos'])) ? $data['monogram1_pos'] : null;
        $this->monogram1_font       = (isset($data['monogram1_font'])) ? $data['monogram1_font'] : null;
        $this->monogram1_color_font = (isset($data['monogram1_color_font'])) ? $data['monogram1_color_font'] : null;
        $this->monogram1_text       = (isset($data['monogram1_text'])) ? $data['monogram1_text'] : null;

        $this->monogram2_pos        = (isset($data['monogram2_pos'])) ? $data['monogram2_pos'] : null;
        $this->monogram2_font       = (isset($data['monogram2_font'])) ? $data['monogram2_font'] : null;
        $this->monogram2_color_font = (isset($data['monogram2_color_font'])) ? $data['monogram2_color_font'] : null;
        $this->monogram2_text       = (isset($data['monogram2_text'])) ? $data['monogram2_text'] : null;

        $this->redactor_comment       = (isset($data['redactor_comment'])) ? $data['redactor_comment'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public $inputFilter;

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'order_cloth_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'cloth_type',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'measurement_type',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'fabric_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'brand_label',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}