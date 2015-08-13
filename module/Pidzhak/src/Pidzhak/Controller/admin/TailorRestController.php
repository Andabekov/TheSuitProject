<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 23/07/2015
 * Time: 13:52
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TailorRestController extends AbstractRestfulController
{
    protected $tailorTable;

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
        $tailors= $this->getTailorTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getTailorTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $tailors->toArray(),
            "total"=> $count,
        ));
    }

    public function getTailorTable(){
        if (!$this->tailorTable) {
            $sm = $this->getServiceLocator();
            $this->tailorTable = $sm->get('Pidzhak\Model\admin\TailorTable');
        }
        return $this->tailorTable;
    }
}