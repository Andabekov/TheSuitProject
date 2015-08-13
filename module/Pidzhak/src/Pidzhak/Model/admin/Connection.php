<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 17:38
 */

namespace Pidzhak\Model\admin;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Connection implements InputFilterAwareInterface
{
    public $supplier_id;
    public $fabric_class;

    public function exchangeArray($data)
    {
        $this->supplier_id  = (isset($data['supplier_id'])) ? $data['supplier_id'] : null;
        $this->fabric_class = (isset($data['fabric_class'])) ? $data['fabric_class'] : null;
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}