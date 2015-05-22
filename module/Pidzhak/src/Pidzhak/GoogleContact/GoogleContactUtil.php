<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 18/05/2015
 * Time: 13:12
 */

namespace Pidzhak\GoogleContact;

class GoogleContactUtil
{
    public static function saveGoogleContact() {

        $contact = new ContactModel();
        $contact->firstname = 'Test';
        $contact->lastname = 'Testovich';
        $contact->email = 'andabekov_az@mail.ru';
        $contact->mobilephone = '+'.'77016538609';

        $contact_xml = GoogleContactXmlParser::buildContact($contact);

        return GoogleContactXmlParser::sendContactToUrl($contact_xml);

//        return $sent;
    }

    public static function createGoogleContact($access_token){
        return GoogleContactXmlParser::createContact($access_token);
    }
}