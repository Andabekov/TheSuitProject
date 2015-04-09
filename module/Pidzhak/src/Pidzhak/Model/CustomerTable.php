<?php
namespace Pidzhak\Model;

use Zend\Db\TableGateway\TableGateway;

class CustomerTable
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

    public function getCustomer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCustomer(Customer $customer)
    {
        $data = array(
            'firstname' => $customer->firstname,
            'lastname' => $customer->lastname,
            'middlename' => $customer->middlename,
            'birthday' => $customer->birthday,
            'mobilephone' => $customer->mobilephone,
            'email' => $customer->email,
            'country' => $customer->country,
            'city' => $customer->city,
            'address' => $customer->address,
            'homephone' => $customer->homephone,
            'work' => $customer->work,
            'position' => $customer->position,
            'workaddress' => $customer->workaddress,
            'workphone' => $customer->workphone,
        );

        $id = (int) $customer->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCustomer($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Customer id does not exist');
            }
        }
    }

    public function deleteCustomer($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}