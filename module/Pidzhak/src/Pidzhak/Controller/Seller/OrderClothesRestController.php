<?php
namespace Pidzhak\Controller\Seller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class OrderClothesRestController extends AbstractRestfulController
{
    protected $orderclothesTable;


    public function create($data)
    {

        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];
        $order_id = $data['order_id'];
        if(!empty($data['seller_name']))
            $seller_name = $data['seller_name'];
        else
            $seller_name = '';
        $order_status = $data['order_status'];

        $offset = intval($rowCount) * (intval($current)-1);
        $customers= $this->getOrderClothesTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase, $order_id, $seller_name, $order_status);
        $count= $this->getOrderClothesTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getOrderClothesTable()
    {
        if (!$this->orderclothesTable) {
            $sm = $this->getServiceLocator();
            $this->orderclothesTable = $sm->get('Pidzhak\Model\Seller\OrderClothesTable');
        }
        return $this->orderclothesTable;
    }
}

