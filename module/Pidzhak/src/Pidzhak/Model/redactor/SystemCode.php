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
    public $system_code;
    public $fabric_id;
    public $description;

    public function exchangeArray($data)
    {
        $this->id          = (isset($data['id'])) ? $data['id'] : null;
        $this->system_code = (isset($data['system_code'])) ? $data['system_code'] : null;
        $this->fabric_id   = (isset($data['fabric_id'])) ? $data['fabric_id'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
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
                'name'     => 'system_code',
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
                'name'     => 'description',
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