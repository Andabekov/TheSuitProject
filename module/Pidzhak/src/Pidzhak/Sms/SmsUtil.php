<?php
/**
 * Created by PhpStorm.
 * User: yerganat
 * Date: 4/23/15
 * Time: 11:08 PM
 */

namespace Pidzhak\Sms;


class SmsUtil {

    public static function sendSmsWithDbWrite($sms, $tableGateWay){
        $tableGateWay->saveSms($sms);
        $lastInsertedSms = $tableGateWay->insertedSms();

        $saved_sms = $tableGateWay->getSms($lastInsertedSms);

        if($saved_sms==null)
            return;


        $numbers_array = SmsXmlParser::add_numbers_to_send_sms($sms->number, $lastInsertedSms);

        $send_xml = SmsXmlParser::buildSendSms($saved_sms->text, $numbers_array);

        $response_xml = SmsXmlParser::sendSmsToUrl($send_xml);

        $RESPONSE = SmsXmlParser::parseResponseXml($response_xml);

        if($RESPONSE==null)
            return;

        $saved_sms->first_status = $RESPONSE->status;
        $saved_sms->credits = $RESPONSE->credits;

        $tableGateWay->saveSms($saved_sms);

    }

}