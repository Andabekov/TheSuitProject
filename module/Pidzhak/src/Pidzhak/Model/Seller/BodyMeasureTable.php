<?php
namespace Pidzhak\Model\Seller;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class BodyMeasureTable
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

    public function fetchPage($rowCount, $offset){
        $resultSet = $this->tableGateway->select(function(Select $select) use ($rowCount, $offset){
            $select->limit($rowCount)->offset($offset);
        });

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getBodyMeasure($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }



    public function getBodyMeasureByCustomerAndClother($customer_id, $clother_id)
    {
        $customer_id  = (int) $customer_id;
        $clother_id  = (int) $clother_id;
        $rowset = $this->tableGateway->select(array('customer_id'=> $customer_id, 'clother_id' => $clother_id ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $customer_id");
        }
        return $row;
    }

    public function saveBodyMeasure(BodyMeasure $bodymeasure)
    {
        $data = array(
            'clother_id' => $bodymeasure->clother_id,
            'customer_id' => $bodymeasure->customer_id,
            'growth' => $bodymeasure->growth,
            'weight' => $bodymeasure->weight,
            'arm_position' => $bodymeasure->arm_position,
            'neck' => $bodymeasure->neck,
            'chest' => $bodymeasure->chest,
            'stomach' => $bodymeasure->stomach,
            'seat' => $bodymeasure->seat,
            'thigh' => $bodymeasure->thigh,
            'knee_finished' => $bodymeasure->knee_finished,
            'pant_bottom_finished' => $bodymeasure->pant_bottom_finished,
            'u_rise' => $bodymeasure->u_rise,
            'otseam_l_and_r' => $bodymeasure->otseam_l_and_r,
            'nape_to_waist' => $bodymeasure->nape_to_waist,
            'front_waist_length' => $bodymeasure->front_waist_length,
            'back_waist_height' => $bodymeasure->back_waist_height,
            'front_waist_height' => $bodymeasure->front_waist_height,
            'biceps' => $bodymeasure->biceps,
            'back_shoulder' => $bodymeasure->back_shoulder,
            'right_sleeve' => $bodymeasure->right_sleeve,
            'left_sleeve' => $bodymeasure->left_sleeve,
            'back_length' => $bodymeasure->back_length,
            'overcoat_back_length' => $bodymeasure->overcoat_back_length,
            'waist' => $bodymeasure->waist,
            'right_wrist' => $bodymeasure->right_wrist,
            'left_wrist' => $bodymeasure->left_wrist,
            'style' => $bodymeasure->style,
        );

        $id = (int) $bodymeasure->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBodyMeasure($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('BodyMeasure id does not exist');
            }
        }
    }

    public function deleteBodyMeasure($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}