<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:56
 */

namespace Pidzhak\Model\admin;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Fabric implements InputFilterAwareInterface
{
    public $id;
    public $fabric_class;
    public $fabric_available_start_date;

    public function exchangeArray($data)
    {
        $this->id                           = (isset($data['id'])) ? $data['id'] : null;
        $this->fabric_class                 = (isset($data['fabric_class'])) ? $data['fabric_class'] : null;
        $this->fabric_available_start_date  = (isset($data['fabric_available_start_date'])) ? $data['fabric_available_start_date'] : null;
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
                'name'     => 'fabric_class',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'fabric_available_start_date',
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