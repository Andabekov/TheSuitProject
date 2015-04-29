<?php
/**
 * Created by PhpStorm.
 * User: yerganat
 * Date: 4/22/15
 * Time: 5:38 PM
 */

namespace Pidzhak\Sms;


use SimpleXMLElement;
use Zend\Http\Client;


class SmsXmlParser
{

    private static $uri = 'https://sms-yessms.com/xml/xml.php';
    private static $username = 'pidzhakkz';
    private static $password = 'eRywRApQ';
    private static $sender = 'MESSAGE';
    private static $header = 'Бутик "Smoking"
';

    public static function testXml()
    {
        $sendxml = <<<XML
        <SMS>
            <operations>
                <operation>SEND</operation>
            </operations>
            <authentification>
                <username></username>
                <password></password>
            </authentification>
            <message>
                <sender></sender>
                <text></text>
            </message>
            <numbers>
                <number>1</number>
                <number>2</number>
                <number  messageID="msg11">3</number>
                <number  messageID="msg12"  variables="var1;var2;var3;">4</number>
            </numbers>
        </SMS>
XML;

        $response_xml = <<<XML
        <RESPONSE>
            <status>status_code</status>
            <credits></credits>
        </RESPONSE>
XML;


        $status_sms = <<<XML
        <SMS>
            <operations>
                <operation>GETSTATUS</operation>
            </operations>
            <authentification>
                <username></username>
                <password></password>
            </authentification>
            <statistics>
                <messageid></messageid>
                <messageid></messageid>
            </statistics>
        </SMS>
XML;

        $numbers_array = array(
            array(
                number => '77019413163',
                messageID => 10,
                variables => ''
            ),
            array(
                number => '',
                messageID => 11,
                variables => ''
            )
        );

        $status_response_sms = <<<XML
        <deliveryreport>
            <message id="msgID"  sentdate="xxxxx" donedate="xxxxx" status="xxxxxx"/>
            <message id="msgID" sentdate="xxxxx"  donedate="xxxxx" status="xxxxxx"/>
        </deliveryreport>
XML;

        $numbers_array = SmsXmlParser::add_numbers_to_send_sms('77019413163', 10);
        $numbers_array = SmsXmlParser::add_numbers_to_send_sms('77016538609', 11, $numbers_array = $numbers_array);

        $send_xml = SmsXmlParser::buildSendSms("Text, текст", $numbers_array);
        var_dump("<br>");
        var_dump($send_xml);

        $msgIdsArray = SmsXmlParser::add_msg_id_to_status_sms(10);
        $msgIdsArray = SmsXmlParser::add_msg_id_to_status_sms(11, $msgIdsArray = $msgIdsArray);

        $status_xml = SmsXmlParser::buildStatusSms($msgIdsArray);
        var_dump("<br>");
        var_dump($status_xml);


        $resp_xml_dom = new SimpleXMLElement($response_xml);
        var_dump(json_encode($resp_xml_dom));

        $stat_resp_sms_dom = new SimpleXMLElement($status_response_sms);
        var_dump(json_encode($stat_resp_sms_dom));


    }

    public static function sendSmsTest(){

        $numbers_array = SmsXmlParser::add_numbers_to_send_sms('77019413163', 10);
        $numbers_array = SmsXmlParser::add_numbers_to_send_sms('77016538609', 11, $numbers_array = $numbers_array);

        $send_xml = SmsXmlParser::buildSendSms("Text, текст, php zend рулить", $numbers_array);

        self::sendSmsToUrl($send_xml);
    }

    public static function checkSmsStatusTest(){

        $msgIdsArray = SmsXmlParser::add_msg_id_to_status_sms(1);

        $send_xml = SmsXmlParser::buildStatusSms($msgIdsArray);

        self::sendSmsToUrl($send_xml);
    }

    private static function buildOper($operation)
    {

        $oper_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<operations/>
XML;

        $operations = new SimpleXMLElement($oper_xml);
        $operations->addChild("operation", $operation);

        return $operations;
    }

    private static function buildAuth()
    {
        $auth_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <authentification/>
XML;
        $auth = new SimpleXMLElement($auth_xml);
        $auth->addChild("username", self::$username);
        $auth->addChild("password", self::$password);
        return $auth;
    }

    private static function buildMsg($sender, $text)
    {
        $message_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <message/>
XML;
        $text = self::$header.$text;
        $message = new SimpleXMLElement($message_xml);
        $message->addChild("sender", $sender);

        $message->addChild("text", $text);
        return $message;
    }

    private static function buildStat($msgIdsArray)
    {
        $statistics_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <statistics/>
XML;
        $statistics = new SimpleXMLElement($statistics_xml);
        foreach ($msgIdsArray as $msgId) {
            $statistics->addChild("messageid", $msgId);
        }
        return $statistics;
    }

    private static function buildNumbers($numberMapsArray)
    {
        $numbers_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <numbers/>
XML;
        $numbers = new SimpleXMLElement($numbers_xml);
        foreach ($numberMapsArray as $numberMaps) {
            if (empty($numberMaps[number])) throw new SmsException("number is not passed");
            if (empty($numberMaps[messageID])) throw new SmsException("messageID is not passed!!!");
            $number = $numbers->addChild("number", $numberMaps[number]);
            $number->addAttribute("messageID", $numberMaps[messageID]);
            if (!empty($numberMaps[variables]))
                $number->addAttribute("variables", $numberMaps[variables]);
        }
        return $numbers;
    }


    public function buildSendSms($text, $numberMapsArray)
    {
        $SMS_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <SMS/>
XML;
        $sms = new SimpleXMLElement($SMS_xml);
        self::xml_adopt($sms, self::buildOper('SEND'));
        self::xml_adopt($sms, self::buildAuth());
        self::xml_adopt($sms, self::buildMsg(self::$sender, $text));
        self::xml_adopt($sms, self::buildNumbers($numberMapsArray));
        return $sms->asXML();

    }



    public static function parseResponseXml($response_xml)
    {
        $response = new SimpleXMLElement($response_xml);
        if (strcasecmp(trim($response->getName()), trim('RESPONSE')) != 0)
            return null;

        return $response;
    }


    public static function buildStatusSms($msgIdsArray = array())
    {
        $SMS_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <SMS/>
XML;
        $sms = new SimpleXMLElement($SMS_xml);
        self::xml_adopt($sms, self::buildOper('GETSTATUS'));
        self::xml_adopt($sms, self::buildAuth());
        self::xml_adopt($sms, self::buildStat($msgIdsArray));
        return $sms->asXML();

    }


    public static function parseDeliveryreportXml($response_xml)
    {
        $deliveryreport = new SimpleXMLElement($response_xml);

        if (strcasecmp(trim($deliveryreport->getName()), trim('deliveryreport')) != 0)
            return null;

        return $deliveryreport;
    }


    public static function xml_adopt($root, $new)
    {
        $node = $root->addChild($new->getName(), (string)$new);
        foreach ($new->attributes() as $attr => $value) {
            $node->addAttribute($attr, $value);
        }
        foreach ($new->children() as $ch) {
            self::xml_adopt($node, $ch);
        }
    }

    public static function add_numbers_to_send_sms($number, $messageID, $variables = null, $numbers_array = array())
    {
        $num_arr = array(
            number => $number,
            messageID => $messageID,
            variables => $variables
        );
        array_push($numbers_array, $num_arr);

        return $numbers_array;
    }

    public static function add_msg_id_to_status_sms($msg_id, $msg_id_array = array())
    {
        array_push($msg_id_array, $msg_id);

        return $msg_id_array;
    }


    public static function sendSmsToUrl($sms_xml){

        //var_dump("<br>");
        //var_dump($sms_xml);
        $client = new Client();
        $client->setUri(self::$uri);

        $client->setOptions(array(
            'sslverifypeer' => false
        ));
        //$client->setUri("http://google.com");

        $request = $client->getRequest();
        $request->setMethod('POST');
        $request->setContent($sms_xml);

        //var_dump($request);

        $client->setRequest($request);
        $response = $client->send();

        //var_dump($response->getBody());

        return $response->getBody();
    }

}



/*first_status ***************************
AUTH_FAILED -1 Неправильный логин и/или пароль либо аккаунт заблокирован
XML_ERROR -2 Неправильный формат XML
NOT_ENOUGH_CREDITS -3 Недостаточно кредитов на аккаунте пользователя
NO_ROUTES -4 Нет корректных номеров получателей либо отправка по указанным маршрутам запрещена для Вашего аккаунта
NO_SENDER -5 Используемое имя отправителя не разрешено для Вашего аккаунта
NO_TEXT -5 Текст сообщения не указан
SEND_OK >  0 Сообщение успешно отправлено
*********************************/



/*status ***************************
PENDING Сообщение ожидает отправки
SENT Отправлено
NOT_DELIVERED Не доставлено
DELIVERED Доставлено

data format (d.m.Y H:i)
*********************************/