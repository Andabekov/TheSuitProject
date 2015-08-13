<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 17:27
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class SupplierRestController extends AbstractRestfulController
{
    protected $supplierTable;

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
        $customers= $this->getSupplierTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getSupplierTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    public function getSupplierTable()
    {
        if (!$this->supplierTable) {
            $sm = $this->getServiceLocator();
            $this->supplierTable = $sm->get('Pidzhak\Model\admin\SupplierTable');
        }
        return $this->supplierTable;
    }
}