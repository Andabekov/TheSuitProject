<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 18/05/2015
 * Time: 13:12
 */

namespace Pidzhak\GoogleContact;

require_once 'google-api-php-client/autoload.php';
require_once 'google-api-php-client/src/Google/Client.php';

session_start();

use Google_Client;
use SimpleXMLElement;
use Zend\Http\Client;
use Pidzhak\GoogleContact\ContactModel;

class GoogleContactXmlParser
{


    public static function buildContact(ContactModel $contact){
        $ROOT_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<atom:entry xmlns:atom='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005'></atom:entry>
XML;
        $contact_xml = new SimpleXMLElement($ROOT_xml);
        self::xml_adopt($contact_xml, self::buildCategory());
        self::xml_adopt($contact_xml, self::buildName($contact->firstname, $contact->lastname));
        self::xml_adopt($contact_xml, self::buildContent());
        self::xml_adopt($contact_xml, self::buildWorkEmail($contact->email, $contact->firstname, $contact->lastname));
        self::xml_adopt($contact_xml, self::buildHomeEmail($contact->email));
        self::xml_adopt($contact_xml, self::buildWorkPhone($contact->mobilephone));
        self::xml_adopt($contact_xml, self::buildHomePhone($contact->mobilephone));

        return $contact_xml->asXML();
    }

    public static function buildCategory(){
        $category_xml = <<<XML
<atom:category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/contact/2008#contact'></atom:category>
XML;
        $category = new SimpleXMLElement($category_xml);

        return $category;
    }

    public static function buildName($firstname, $lastname){
        $name_xml = <<<XML
<gd:name></gd:name>
XML;
        $name = new SimpleXMLElement($name_xml);
        $name->addChild("gd:givenName", $firstname);
        $name->addChild("gd:familyName", $lastname);
        $name->addChild("gd:fullName", $firstname.' '.$lastname);

        return $name;
    }


    public static function buildContent(){
        $content_xml = <<<XML
<atom:content type='text'>Notes</atom:content>
XML;
        $content = new SimpleXMLElement($content_xml);
        return $content;
    }

    public static function buildWorkEmail($email, $firstname, $lastname){
        $work_email_xml = <<<XML
<gd:email rel='http://schemas.google.com/g/2005#work' primary='true'></gd:email>
XML;
        $work_email = new SimpleXMLElement($work_email_xml);
        $work_email->addAttribute("address", $email);
        $work_email->addAttribute("displayName", $firstname.' '.$lastname);
        return $work_email;
    }

    public static function buildHomeEmail($email){
        $work_email_xml = <<<XML
<gd:email rel='http://schemas.google.com/g/2005#home'></gd:email>
XML;
        $work_email = new SimpleXMLElement($work_email_xml);
        $work_email->addAttribute("address", $email);
        return $work_email;
    }

    public static function buildWorkPhone($phone){
        $work_phone_xml = <<<XML
<gd:phoneNumber rel='http://schemas.google.com/g/2005#work' primary='true'>$phone</gd:phoneNumber>
XML;
        $work_phone = new SimpleXMLElement($work_phone_xml);
        return $work_phone;
    }

    public static function buildHomePhone($phone){
        $home_phone_xml = <<<XML
<gd:phoneNumber rel='http://schemas.google.com/g/2005#home'>$phone</gd:phoneNumber>
XML;
        $home_phone = new SimpleXMLElement($home_phone_xml);
        return $home_phone;
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

    public static function sendContactToUrl($contact_xml){

        //var_dump("<br>");
        //var_dump($sms_xml);

        session_start();

        $google_client = new Google_Client();
        $google_client->setClientId('953149846914-4mahib5a15dkpnor8c72d1am9sp120c7.apps.googleusercontent.com');
        $google_client->setClientSecret('W7UNgwbIfmdnlRnkwaZRQbl4');
        $google_client->setRedirectUri('http://localhost:8080');
        $google_client->addScope("https://www.googleapis.com/auth/urlshortener");

//        $google_client->authenticate('and08275621');

        if (!isset($_SESSION['token'])) {
            $google_client->authenticate('and08275621');
            $_SESSION['token'] = $google_client->getAccessToken();
        } else {
            $authObj = json_decode($_SESSION['token']);
            $accessToken = $authObj->access_token;
        }

//        $service = new Google_Service_Urlshortener($google_client);

        $client = new Client();
        $client->setOptions(array('sslverifypeer' => false));
        $client->setUri('https://www.google.com/m8/feeds/contacts/andabekov1@gmail.com/full?access_token='.$accessToken);
//        $client->setUri('https://www.google.com/m8/feeds/contacts/953149846914-4mahib5a15dkpnor8c72d1am9sp120c7.apps.googleusercontent.com/full');

//        $client->setOptions(array(
//            'sslverifypeer' => false
//        ));
        //$client->setUri("http://google.com");

        $request = $client->getRequest();
        $request->setMethod('POST');
        $request->setContent($contact_xml);

        //var_dump($request);

        $client->setRequest($request);
        $response = $client->send();

        //var_dump($response->getBody());

        return $response->getBody();
    }
}