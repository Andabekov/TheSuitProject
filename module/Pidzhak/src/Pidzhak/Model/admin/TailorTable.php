<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 23/07/2015
 * Time: 14:02
 */

namespace Pidzhak\Model\admin;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class TailorTable
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
        $resultSet = $this->tableGateway->select(function(Select $select) use ($rowCount, $offset, $orderby, $searchPhrase){
            if($rowCount<0)
                $select->offset(0);
            else
                $select->limit($rowCount)->offset($offset);
            $select->order($orderby);

        });
        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getTailor($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTailor(Tailor $tailor)
    {
        $data = array(
            'name' => $tailor->name,
        );

        $id = (int) $tailor->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTailor($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Tailor_id does not exist');
            }
        }
    }

    public function deleteTailor($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}