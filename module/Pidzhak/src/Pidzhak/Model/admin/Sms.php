<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 11:03
 */

namespace Pidzhak\Model\admin;

class Sms
{
    public $id;
    public $text;
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


}