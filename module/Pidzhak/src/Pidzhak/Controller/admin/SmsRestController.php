<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 14:37
 */

namespace Pidzhak\Controller\admin;

use Pidzhak\Sms\SmsXmlParser;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class SmsRestController extends AbstractRestfulController
{
    protected $smsTable;

    public function get($id)
    {

        $this->updateSms(array($id));

        return new JsonModel(array(
            'status_ok' => 'ok',
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

        $offset = intval($rowCount) * (intval($current)-1);
        $smss= $this->getSmsTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getSmsTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $smss->toArray(),
            "total"=> $count,
        ));
    }

    public function updateSms($msgIdsArray){
        $send_xml = SmsXmlParser::buildStatusSms($msgIdsArray);
        $response_xml = SmsXmlParser::sendSmsToUrl($send_xml);
        if($response_xml==null)
            return;


        $deliveryreport = SmsXmlParser::parseDeliveryreportXml($response_xml);

        if($deliveryreport==null)
            return;

        foreach ($deliveryreport->message as $message) {
            $msgId= (string)$message['id'];
            $deliverystatus = (string)$message['status'];
            $sentdate = (string)$message['sentdate'];
            $donedate = (string)$message['donedate'];
            $sms = $this->getSmsTable()->getSms($msgId);
            $sms->status = $deliverystatus;

            $sms->sentdate =date( 'Y-m-d H:i:s', date_create_from_format('d.m.Y H:i', $sentdate)->getTimestamp());
            $sms->donedate =date( 'Y-m-d H:i:s', date_create_from_format('d.m.Y H:i', $donedate)->getTimestamp());
            $this->getSmsTable()->saveSms($sms);

        }

    }

    /*Inversion of Control*/
    public function getSmsTable()
    {
        if (!$this->smsTable) {
            $sm = $this->getServiceLocator();
            $this->smsTable = $sm->get('Pidzhak\Model\admin\SmsTable');
        }
        return $this->smsTable;
    }
}