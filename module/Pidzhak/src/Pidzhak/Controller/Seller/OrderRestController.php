<?php
namespace Pidzhak\Controller\seller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class OrderRestController extends AbstractRestfulController
{
    protected $orderTable;

    public function create($data)
    {

        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];
        $status_id = $data['status_id'];

        $offset = intval($rowCount) * (intval($current)-1);
        $customers= $this->getOrderTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase, $status_id);
        $count= $this->getOrderTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getOrderTable()
    {
        if (!$this->orderTable) {
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Pidzhak\Model\seller\OrderTable');
        }
        return $this->orderTable;
    }
}

