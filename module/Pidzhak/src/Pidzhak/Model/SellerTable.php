<?php
namespace Pidzhak\Model;

use Zend\Db\TableGateway\TableGateway;

class SellerTable
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

    public function getSeller($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSeller(Seller $seller)
    {
        $data = array(
            'description' => $seller->description,
            'title'  => $seller->title,
        );

        $id = (int) $seller->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getSeller($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Seller id does not exist');
            }
        }
    }

    public function deleteSeller($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}