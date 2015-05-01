<?php
namespace Pidzhak\Model\Seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase, $order_id)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('cyclestable', 'orderclothes.cycle_id = cyclestable.id')
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id');
        if ($rowCount < 0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);
        //$select->order($orderby);

        /*if($searchPhrase)
            $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');*/


        if ($order_id != -1) {
            $where = new  Where();
            $where->equalTo('order_id', $order_id);
            $select->where($where);
        }

        //you can check your query by echo-ing :
        // echo $select->getSqlString();
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getCountOfClothesByOrder($order_id)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($order_id) {
            $where = new  Where();
            $where->equalTo('order_id', $order_id);
            $select->where($where);
        });
        return $resultSet->count();
    }

    public function getOrderClothes($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveOrderClothes(OrderClothes $orderclothes)
    {
        $data = array(
            'order_id' => $orderclothes->order_id,
            'cycle_id' => $orderclothes->cycle_id,
            'preferred_date' => $orderclothes->preferred_date,
            'product_id' => $orderclothes->product_id,
            'pricelistnum' => $orderclothes->pricelistnum,
            'actual_amount' => $orderclothes->actual_amount,
            'paytype_id' => $orderclothes->paytype_id,
            'textile_id' => $orderclothes->textile_id,
            'typeof_measure' => $orderclothes->typeof_measure,
            'label_brand' => $orderclothes->label_brand,
            'style_id' => $orderclothes->style_id,
            'first_monogram_location' => $orderclothes->first_monogram_location,
            'first_monogram_font' => $orderclothes->first_monogram_font,
            'first_monogram_font_color' => $orderclothes->first_monogram_font_color,
            'first_monogram_caption' => $orderclothes->first_monogram_caption,
            'second_monogram_location' => $orderclothes->second_monogram_location,
            'second_monogram_font' => $orderclothes->second_monogram_font,
            'second_monogram_font_color' => $orderclothes->second_monogram_font_color,
            'second_monogram_caption' => $orderclothes->second_monogram_caption,
            'seller_comment' => $orderclothes->seller_comment,
        );


        $id = (int)$orderclothes->id;
        if ($id == 0) {
            $retval = $this->tableGateway->insert($data);
        } else {
            if ($this->getOrderClothes($id)) {
                $retval = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('OrderClothes id does not exist');
            }
        }

    }

    public function deleteOrderClothes($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id));
    }


}