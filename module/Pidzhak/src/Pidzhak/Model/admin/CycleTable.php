<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:57
 */

namespace Pidzhak\Model\admin;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CycleTable
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase){
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

    public function getCycle($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCycle(Cycle $cycle)
    {
        $data = array(
            'order_accept_start_date' => $cycle->order_accept_start_date,
            'order_accept_finish_date' => $cycle->order_accept_finish_date,
            'order_check_deadline_date' => $cycle->order_check_deadline_date,
            'submit_deadline_date' => $cycle->submit_deadline_date,
            'ship_deadline_date' => $cycle->ship_deadline_date,
            'arrive_deadline_date' => $cycle->arrive_deadline_date,
            'cycle_active' => $cycle->cycle_active,
        );

        $id = (int) $cycle->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCycle($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Cycle_id does not exist');
            }
        }
    }

    public function deleteCycle($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}