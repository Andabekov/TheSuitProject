<?php
namespace Pidzhak\Controller\Seller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Pidzhak\Model\Seller\Customer;
use Zend\View\Model\JsonModel;

class CustomerRestController extends AbstractRestfulController
{
    protected $customerTable;

    public function get($id)
    {
        $customer= $this->getCustomerTable()->getCustomer($id);
        return new JsonModel(array(
            'firstname' => $customer->firstname,
            "lastname"=> $customer->lastname,
            "mobilephone"=> $customer->mobilephone,
        ));
    }



    public function create($data)
    {

        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];
        $bdmode = $data['bdmode'];



        $offset = intval($rowCount) * (intval($current)-1);
        $customers= $this->getCustomerTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase, $bdmode);
        $count= $this->getCustomerTable()->getCount();



/*        foreach($customers as $customer){
            print_r($customer->id);
        }*/



        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Pidzhak\Model\Seller\CustomerTable');
        }
        return $this->customerTable;
    }
}

