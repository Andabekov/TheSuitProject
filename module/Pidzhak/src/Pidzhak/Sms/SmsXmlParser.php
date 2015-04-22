<?php
/**
 * Created by PhpStorm.
 * User: yerganat
 * Date: 4/22/15
 * Time: 5:38 PM
 */

namespace Pidzhak\Sms;


use SimpleXMLElement;

class SmsXmlParser
{

    private $dom;

    public function buildDom()
    {
        $xml = <<<XML
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

// SimpleXML use case
        $simplexml = new SimpleXMLElement($xml);
        var_dump(json_encode($simplexml));
        var_dump($simplexml->operations->operation);

    }

}
