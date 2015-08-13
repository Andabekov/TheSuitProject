<?php
namespace Pidzhak\Model\seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ClotherMeasureTable
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

    public function fetchPage($rowCount, $offset)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($rowCount, $offset) {
            $select->limit($rowCount)->offset($offset);
        });

        return $resultSet;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getClotherMeasure($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getMeasureByOrderClothId($order_cloth_id){
        $order_cloth_id  = (int) $order_cloth_id;
        $rowset = $this->tableGateway->select(array('order_cloth_id'=> $order_cloth_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $order_cloth_id");
        }
        return $row;
    }

    public function getMeasure($cloth_type, $order_id){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('ordertable');

        $where = new Where();
        $where->equalTo('id', $order_id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();

        return $this->getCMbyOCIDandClothType($row->customer_id, $cloth_type);
    }

    public function getClotherMeasureByCustomerAndClother($customer_id, $clother_id)
    {
        $customer_id = (int)$customer_id;
        $clother_id = (int)$clother_id;
        $rowset = $this->tableGateway->select(array('customer_id' => $customer_id, 'clother_id' => $clother_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $customer_id");
        }

        return $row;
    }

    public function getCMbyOCIDandClothType($order_cloth_id, $clother_id){
        $order_cloth_id = (int)$order_cloth_id;
        $clother_id = (int)$clother_id;
        $rowset = $this->tableGateway->select(array('order_cloth_id' => $order_cloth_id, 'clother_id' => $clother_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $order_cloth_id");
        }

        return $row;
    }

    public function saveClotherMeasure(ClotherMeasure $clothermeasure)
    {
        $data = array(
            'id' => $clothermeasure->id,
            'clother_id' => $clothermeasure->clother_id,
            'customer_id' => $clothermeasure->customer_id,
            'growth' => $clothermeasure->growth,
            'weight' => $clothermeasure->weight,
            'chest_finished' => $clothermeasure->chest_finished,
            'stomach_finished' => $clothermeasure->stomach_finished,
            'jacket_seat_finished' => $clothermeasure->jacket_seat_finished,
            'biceps_finished' => $clothermeasure->biceps_finished,
            'left_sleeve_finished' => $clothermeasure->left_sleeve_finished,
            'right_sleeve_finished' => $clothermeasure->right_sleeve_finished,
            'back_length_finished' => $clothermeasure->back_length_finished,
            'front_length_finished' => $clothermeasure->front_length_finished,
            'shoulder_finished' => $clothermeasure->shoulder_finished,
            'waist_finished' => $clothermeasure->waist_finished,
            'seat_finished' => $clothermeasure->seat_finished,
            'thigh_finished' => $clothermeasure->thigh_finished,
            'outseam_l_and_r_finished' => $clothermeasure->outseam_l_and_r_finished,
            'knee_finished' => $clothermeasure->knee_finished,
            'pant_bottom_finished' => $clothermeasure->pant_bottom_finished,
            'u_rise_finished' => $clothermeasure->u_rise_finished,
            'right_cuff' => $clothermeasure->right_cuff,
            'left_cuff' => $clothermeasure->left_cuff,
            'shirt_neck' => $clothermeasure->shirt_neck,
            'order_cloth_id' => $clothermeasure->order_cloth_id,
        );

        $id = (int)$clothermeasure->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getClotherMeasure($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('ClotherMeasure id does not exist');
            }
        }
    }

    public function deleteClotherMeasure($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id));
    }
}