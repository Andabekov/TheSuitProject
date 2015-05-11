<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 03/05/2015
 * Time: 18:04
 */

namespace Pidzhak\Model\redactor;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class SystemCode implements InputFilterAwareInterface
{
    public $id;
    public $order_cloth_id;
    public $code;
    public $fabric_optional;
    public $description;

    public function exchangeArray($data)
    {
        $this->id              = (isset($data['id'])) ? $data['id'] : null;
        $this->order_cloth_id  = (isset($data['order_cloth_id'])) ? $data['order_cloth_id'] : null;
        $this->code            = (isset($data['code'])) ? $data['code'] : null;
        $this->fabric_optional = (isset($data['fabric_optional'])) ? $data['fabric_optional'] : null;
        $this->description     = (isset($data['description'])) ? $data['description'] : null;
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
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'code',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'fabric_optional',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'description',
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