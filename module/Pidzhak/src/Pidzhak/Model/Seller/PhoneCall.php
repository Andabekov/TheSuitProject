<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 07/06/2015
 * Time: 23:35
 */

namespace Pidzhak\Model\Seller;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class PhoneCall implements InputFilterAwareInterface
{
    public $id;
    public $call_date;
    public $appoint_date;
    public $appoint_comment;
    public $remind_date;
    public $remind_comment;
    public $media;
    public $client_id;
    public $seller_id;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->call_date = (!empty($data['call_date'])) ? $data['call_date'] : null;
        $this->appoint_date = (!empty($data['appoint_date'])) ? $data['appoint_date'] : null;
        $this->appoint_comment = (!empty($data['appoint_comment'])) ? $data['appoint_comment'] : null;
        $this->remind_date = (!empty($data['remind_date'])) ? $data['remind_date'] : null;
        $this->remind_comment = (!empty($data['remind_comment'])) ? $data['remind_comment'] : null;
        $this->media = (!empty($data['media'])) ? $data['media'] : null;
        $this->client_id = (!empty($data['client_id'])) ? $data['client_id'] : null;
        $this->seller_id = (!empty($data['seller_id'])) ? $data['seller_id'] : null;
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
                'name'     => 'call_date',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'appoint_date',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'appoint_comment',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'remind_date',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'remind_comment',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));


            $inputFilter->add(array(
                'name'     => 'media',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'client_id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'seller_id',
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