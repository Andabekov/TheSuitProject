<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 16:47
 */

namespace Pidzhak\Model\accountant;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Certificate implements InputFilterAwareInterface
{
    public $id;
    public $given_date;
    public $valid_date;
    public $seller_id;
    public $cost;
    public $status;
    public $comment;
    public $cert_num;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->given_date = (!empty($data['given_date'])) ? $data['given_date'] : null;
        $this->valid_date = (!empty($data['valid_date'])) ? $data['valid_date'] : null;
        $this->seller_id = (!empty($data['seller_id'])) ? $data['seller_id'] : null;
        $this->cost = (!empty($data['cost'])) ? $data['cost'] : null;
        $this->comment = (!empty($data['comment'])) ? $data['comment'] : null;
        $this->cert_num = (!empty($data['cert_num'])) ? $data['cert_num'] : null;

        if($data['status']==0){
            $this->status = 'Использыван';
        } else{
            $this->status = 'Не использыван';
        }
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
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'given_date',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'valid_date',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'seller_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'status',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'cost',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'comment',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'cert_num',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            return $inputFilter;
        }
    }
}