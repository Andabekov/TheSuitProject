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

        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];



        $offset = intval($rowCount) * (intval($current)-1);
        $customers= $this->getCustomerTable()->fetchPage(intval($rowCount), $offset);
        $count= $this->getCustomerTable()->getCount();



/*        foreach($customers as $customer){
            print_r($customer->id);
        }*/



        return new JsonModel(array(
            'current' => $current,
            'rowCount' => $rowCount,
            'rows' => $customers->toArray(),
            "total"=> $count,
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

