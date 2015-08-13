<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 13:53
 */

namespace Pidzhak\Model\accountant;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Task implements InputFilterAwareInterface
{
    public $id;
    public $task_given_date;
    public $task_due_date;
    public $task_type;
    public $task_body;
    public $task_status;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->task_given_date = (!empty($data['task_given_date'])) ? $data['task_given_date'] : null;
        $this->task_due_date   = (!empty($data['task_due_date'])) ? $data['task_due_date'] : null;
        $this->task_type       = (!empty($data['task_type'])) ? $data['task_type'] : null;
        $this->task_body       = (!empty($data['task_body'])) ? $data['task_body'] : null;
        $this->task_status     = (!empty($data['task_status'])) ? $data['task_status'] : null;
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