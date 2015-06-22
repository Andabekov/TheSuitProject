<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 16:47
 */

namespace Pidzhak\Model\Seller;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class FinanceOperations implements InputFilterAwareInterface
{
    public $id;
    public $oper_date;
    public $oper_type;
    public $oper_comment;
    public $oper_status;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->oper_date = (!empty($data['oper_date'])) ? $data['oper_date'] : null;
        $this->oper_type = (!empty($data['oper_type'])) ? $data['oper_type'] : null;
        $this->oper_comment = (!empty($data['oper_comment'])) ? $data['oper_comment'] : null;

        if($data['oper_status']==0){
            $this->oper_status = 'Не подтвержден';
        } else{
            $this->oper_status = 'Подтвержден';
        }
//        $this->oper_status = (!empty($data['oper_status'])) ? $data['oper_status'] : null;
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
                'name' => 'oper_date',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'oper_type',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'oper_comment',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'oper_status',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            return $inputFilter;
        }
    }
}