<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 11:03
 */

namespace Pidzhak\Model\admin;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Sms implements InputFilterAwareInterface
{
    public $id;
    public $text;
    public $number;
    public $variables;
    public $credits;
    public $sentdate;
    public $donedate;
    public $first_status;
    public $status;
    public $send_sms_xml;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->text = (isset($data['text'])) ? $data['text'] : null;
        $this->number = (isset($data['number'])) ? $data['number'] : null;
        $this->variables = (isset($data['variables'])) ? $data['variables'] : null;
        $this->credits = (isset($data['credits'])) ? $data['credits'] : null;
        $this->sentdate = (isset($data['sentdate'])) ? $data['sentdate'] : null;
        $this->donedate = (isset($data['donedate'])) ? $data['donedate'] : null;
        $this->first_status = (isset($data['first_status'])) ? $data['first_status'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->send_sms_xml = (isset($data['send_sms_xml'])) ? $data['send_sms_xml'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    protected $inputFilter;

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'text',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'number',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }


}