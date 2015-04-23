<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 11:03
 */

namespace Pidzhak\Model\admin;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class SmsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($rowCount, $offset, $orderby, $searchPhrase) {
            if ($rowCount < 0)
                $select->offset(0);
            else
                $select->limit($rowCount)->offset($offset);
            $select->order($orderby);

        });

        return $resultSet;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getSms($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSms(Sms $sms)
    {
        $data = array(
            'text' => $sms->text,
            'variables' => $sms->variables,
            'credits' => $sms->credits,
            'sentdate' => $sms->sentdate,
            'donedate' => $sms->donedate,
            'first_status' => $sms->first_status,
            'status' => $sms->status,
            'send_sms_xml' => $sms->send_sms_xml,
        );

        $id = (int)$sms->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getSms($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Sms_id does not exist');
            }
        }
    }

    public function deleteSms($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id));
    }
}