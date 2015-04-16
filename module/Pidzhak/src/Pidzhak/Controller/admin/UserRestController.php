<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 15/04/2015
 * Time: 15:29
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Pidzhak\Model\admin\User;
use Zend\View\Model\JsonModel;

class UserRestController extends AbstractRestfulController
{
    protected $userTable;

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
        $users= $this->getUserTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getUserTable()->getCount();

        /*        foreach($customers as $customer){
                    print_r($customer->id);
                }*/

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $users->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Pidzhak\Model\admin\UserTable');
        }
        return $this->userTable;
    }
}

