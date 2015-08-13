<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 11/08/2015
 * Time: 12:06
 */

namespace Pidzhak\Model\admin;

use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PenaltyTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(new Expression('DISTINCT style_num, cloth_type'));
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

    public function getPenalty($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePenalty(Penalty $penalty)
    {
        $data = array(
            'penalty_name' => $penalty->penalty_name,
            'penalty_comment' => $penalty->penalty_comment,
        );

        $id = (int) $penalty->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPenalty($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('penalty_id does not exist');
            }
        }
    }

    public function deletePenalty($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}