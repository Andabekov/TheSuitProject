<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 11/08/2015
 * Time: 17:28
 */

namespace Pidzhak\Model\accountant;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Request implements InputFilterAwareInterface
{
    public $id;
    public $request_date;
    public $request_type;
    public $request_body;
    public $request_status;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->request_date   = (!empty($data['request_date'])) ? $data['request_date'] : null;
        $this->request_type   = (!empty($data['request_type'])) ? $data['request_type'] : null;
        $this->request_body   = (!empty($data['request_body'])) ? $data['request_body'] : null;
        $this->request_status = (!empty($data['request_status'])) ? $data['request_status'] : null;
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

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
            return $inputFilter;
        }
    }
}