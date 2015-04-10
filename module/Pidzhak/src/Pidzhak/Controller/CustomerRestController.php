<?php
namespace Pidzhak\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Pidzhak\Model\Customer;
use Zend\View\Model\JsonModel;

class CustomerRestController extends AbstractRestfulController
{
    protected $customerTable;


    public function create($data)
    {
        return new JsonModel(array(
            'current' => 1,
            'rowCount' => 4,
            'rows' => array(
                array(
                    "id" => 0,
                    "sender" => "Item 0",
                    "received" => "$0"
                ),array(
                    "id" => 1,
                    "sender" => "Item 1",
                    "received" => "$1"
                ),array(
                    "id" => 2,
                    "sender" => "Item 2",
                    "received" => "$2"
                ),array(
                    "id" => 3,
                    "sender" => "Item 3",
                    "received" => "$3"
                ),
            ),
            "total"=> 1123
        ));
    }

    /*Inversion of Control*/
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}

