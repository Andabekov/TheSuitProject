<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 18/05/2015
 * Time: 13:12
 */

namespace Pidzhak\GoogleContact;

use SimpleXMLElement;
use Zend\Http\Client;
use Pidzhak\GoogleContact\ContactModel;

class GoogleContactXmlParser
{

    public static function buildContact(ContactModel $contact){
        $CONTACT_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <atom:entry xmlns:atom='http://www.w3.org/2005/Atom'
    xmlns:gd='http://schemas.google.com/g/2005'/>
XML;
        $contact = new SimpleXMLElement($CONTACT_xml);
        self::xml_adopt($contact, self::buildCategory());
        self::xml_adopt($contact, self::buildName($contact->firstname, $contact->lastname));
        self::xml_adopt($contact, self::buildEmailPhone($contact->email, $contact->mobilephone, $contact->firstname, $contact->lastname));

        return $contact->asXML();
    }

    public static function buildCategory(){
        $category_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes" xmlns:atom='http://www.w3.org/2005/Atom'?>
<atom:category scheme='http://schemas.google.com/g/2005#kind'
               term='http://schemas.google.com/contact/2008#contact'/>
XML;
        $category = new SimpleXMLElement($category_xml);

        return $category;
    }

    public static function buildName($firstname, $lastname){
        $name_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<gd:name/>
XML;

        $name = new SimpleXMLElement($name_xml);
        $name->addChild("gd:givenName", $firstname);
        $name->addChild("gd:familyName", $lastname);
        $name->addChild("gd:fullName", $firstname.' '.$lastname);

        return $name;
    }

    public static function buildEmailPhone($email, $phone, $firstname, $lastname){
        $email_phone_xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<atom:content type='text'>Notes</atom:content>
<gd:email rel='http://schemas.google.com/g/2005#work'
          primary='true' address='$email' displayName='$firstname'/>
<gd:email rel='http://schemas.google.com/g/2005#home' address='$email'/>
<gd:phoneNumber rel='http://schemas.google.com/g/2005#work' primary='true'>
    $phone
</gd:phoneNumber>
<gd:phoneNumber rel='http://schemas.google.com/g/2005#home'>
    $phone
</gd:phoneNumber>
<gd:im address='$email' protocol='http://schemas.google.com/g/2005#GOOGLE_TALK'
       primary='true' rel='http://schemas.google.com/g/2005#home'/>
<gd:structuredPostalAddress
        rel='http://schemas.google.com/g/2005#work' primary='true'>
    <gd:city>Mountain View</gd:city>
    <gd:street>1600 Amphitheatre Pkwy</gd:street>
    <gd:region>CA</gd:region>
    <gd:postcode>94043</gd:postcode>
    <gd:country>United States</gd:country>
    <gd:formattedAddress>
        1600 Amphitheatre Pkwy Mountain View
    </gd:formattedAddress>
</gd:structuredPostalAddress>
XML;

        $email_phone = new SimpleXMLElement($email_phone_xml);

        return $email_phone;
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
}