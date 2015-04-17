<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:57
 */

namespace Pidzhak\Model\admin;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Cycle implements InputFilterAwareInterface
{
    public $id;
    public $order_accept_start_date;
    public $order_accept_finish_date;
    public $order_check_deadline_date;
    public $submit_deadline_date;
    public $ship_deadline_date;
    public $arrive_deadline_date;
    public $cycle_active;

    public function exchangeArray($data)
    {
        $this->id                        = (isset($data['id'])) ? $data['id'] : null;
        $this->order_accept_start_date   = (isset($data['order_accept_start_date'])) ? $data['order_accept_start_date'] : null;
        $this->order_accept_finish_date  = (isset($data['order_accept_finish_date'])) ? $data['order_accept_finish_date'] : null;
        $this->order_check_deadline_date = (isset($data['order_check_deadline_date'])) ? $data['order_check_deadline_date'] : null;
        $this->submit_deadline_date      = (isset($data['submit_deadline_date'])) ? $data['submit_deadline_date'] : null;
        $this->ship_deadline_date        = (isset($data['ship_deadline_date'])) ? $data['ship_deadline_date'] : null;
        $this->arrive_deadline_date      = (isset($data['arrive_deadline_date'])) ? $data['arrive_deadline_date'] : null;
        $this->cycle_active              = (isset($data['cycle_active'])) ? $data['cycle_active'] : null;
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
                'name'     => 'order_accept_start_date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'order_accept_finish_date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'order_check_deadline_date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'submit_deadline_date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'ship_deadline_date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'arrive_deadline_date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));
            $inputFilter->add(array(
                'name'     => 'cycle_active',
                'required' => false,
                'value'  => '1'
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}