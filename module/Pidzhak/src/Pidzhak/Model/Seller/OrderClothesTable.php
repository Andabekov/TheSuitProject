<?php
namespace Pidzhak\Model\Seller;

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

            /*if($searchPhrase)
                $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');*/
        });

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getOrderClothes($id)
    {
        $id  = (int) $id;
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
            'cycle_number' => $orderclothes->cycle_number,
            'product_name' => $orderclothes->product_name,
            'textile_class' => $orderclothes->textile_class,
            'textile_number' => $orderclothes->textile_number,
            'typeof_measure' => $orderclothes->typeof_measure,
            'label_brand' => $orderclothes->label_brand,
            'style_number' => $orderclothes->style_number,
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

        $id = (int) $orderclothes->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOrderClothes($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('OrderClothes id does not exist');
            }
        }
    }

    public function deleteOrderClothes($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}