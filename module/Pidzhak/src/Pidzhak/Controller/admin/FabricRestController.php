<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 15:46
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class FabricRestController extends AbstractRestfulController
{
    protected $fabricTable;

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
        $fabrics= $this->getFabricTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getFabricTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $fabrics->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getFabricTable()
    {
        if (!$this->fabricTable) {
            $sm = $this->getServiceLocator();
            $this->fabricTable = $sm->get('Pidzhak\Model\admin\FabricTable');
        }
        return $this->fabricTable;
    }
}