<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 17:23
 */

namespace Pidzhak\Controller\Seller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class FinanceOperationsRest extends AbstractRestfulController
{
    protected $financeOperationsTable;

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
        $customers= $this->getFinanceOperationsTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getFinanceOperationsTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    public function getFinanceOperationsTable()
    {
        if (!$this->financeOperationsTable) {
            $sm = $this->getServiceLocator();
            $this->financeOperationsTable = $sm->get('Pidzhak\Model\Seller\FinanceOperationsTable');
        }
        return $this->financeOperationsTable;
    }
}