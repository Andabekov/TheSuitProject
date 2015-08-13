<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 17:47
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ConnectionRestController extends AbstractRestfulController
{
    protected $connectionTable;

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
        $customers= $this->getConnectionTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getConnectionTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    public function getConnectionTable()
    {
        if (!$this->connectionTable) {
            $sm = $this->getServiceLocator();
            $this->connectionTable = $sm->get('Pidzhak\Model\admin\ConnectionTable');
        }
        return $this->connectionTable;
    }
}