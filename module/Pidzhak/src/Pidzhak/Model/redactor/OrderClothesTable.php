<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 06/05/2015
 * Time: 17:02
 */

namespace Pidzhak\Model\redactor;

use Pidzhak\Model\redactor\OrderClothes;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class OrderClothesTable
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

    public function getOrderClothes($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('order_cloth_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("test Could not find row $id");
        }
        return $row;
    }

    public function saveOrderClothes(OrderClothes $orderClothes)
    {
        $data = array(
            'order_cloth_id'   => $orderClothes->order_cloth_id,
            'cloth_type'       => $orderClothes->cloth_type,
            'measurement_type' => $orderClothes->measurement_type,
            'fabric_id'        => $orderClothes->fabric_id,
            'brand_label'      => $orderClothes->brand_label,

            'monogram1_pos'        => $orderClothes->monogram1_pos,
            'monogram1_font'       => $orderClothes->monogram1_font,
            'monogram1_color_font' => $orderClothes->monogram1_color_font,
            'monogram1_text'       => $orderClothes->monogram1_text,

            'monogram2_pos'        => $orderClothes->monogram2_pos,
            'monogram2_font'       => $orderClothes->monogram2_font,
            'monogram2_color_font' => $orderClothes->monogram2_color_font,
            'monogram2_text'       => $orderClothes->monogram2_text,

            'redactor_comment'       => $orderClothes->redactor_comment,
        );

        $id = (int) $orderClothes->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);

            $sql = new Sql($this->tableGateway->adapter);
            $update = $sql->update();
            $update->table('orderclothes');
            $update->set(array(
                'textile_id' => $orderClothes->fabric_id
            ));

            $update->where(array('id' => $orderClothes->order_cloth_id));
            $statement = $sql->prepareStatementForSqlObject($update);
            $statement->execute();
        } else {
            if ($this->getOrderClothes($id)) {
                $this->tableGateway->update($data, array('id' => $id));

                $sql = new Sql($this->tableGateway->adapter);
                $update = $sql->update();
                $update->table('orderclothes');
                $update->set(array(
                    'textile_id' => $orderClothes->fabric_id
                ));

                $update->where(array('id' => $orderClothes->order_cloth_id));
                $statement = $sql->prepareStatementForSqlObject($update);
                $statement->execute();
            } else {
                throw new \Exception('OrderClothId does not exist');
            }
        }
    }

    public function deleteOrderClothes($id)
    {
        $this->tableGateway->delete(array('order_cloth_id' => (int) $id));
    }
}