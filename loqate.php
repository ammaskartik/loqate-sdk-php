<?php

require dirname(__FILE__) . '/vendor/autoload.php';

use Loqate\ApiConnector\Client\Capture;
use Loqate\ApiConnector\Client\Verify;

$capture = new Capture('YN92-GT38-NX54-PM54');
$verify = new Verify('YN92-GT38-NX54-PM54');

switch ($argv[1]) {
    case 'capture_find':
        $text = (string)readline('Enter text: ');
        $result = $capture->find(['Text' => $text]);
        var_dump($result);
        break;
    case 'capture_retrieve':
        $id = 'ES|LP|B|6041906|B_SPA';
        $result = $capture->retrieve(['Id' => $id]);
        var_dump($result);
        break;
    case 'geolocate':
        $params = ['Latitude' => '52.182728', 'Longitude' => '-2.2221217'];
        $result = $capture->geolocate($params);
        var_dump($result);
        break;
    case 'address_verify':
        $address = (string)readline('Address: ');
        $address1 = (string)readline('Address1: ');
        $country = (string)readline('Country: ');
        $postcode = (string)readline('PostalCode: ');
        $params = [
            "Address" => "",
            "Address1" => $address,
            "Address2" => $address1,
            "Address3" => "",
            "Address4" => "",
            "Address5" => "",
            "Address6" => "",
            "Address7" => "",
            "Address8" => "",
            "Country" => $country,
            "SuperAdministrativeArea" => "",
            "AdministrativeArea" => "",
            "SubAdministrativeArea" => "",
            "Locality" => "",
            "DependentLocality" => "",
            "DoubleDependentLocality" => "",
            "Thoroughfare" => "",
            "DependentThoroughfare" => "",
            "Building" => "",
            "Premise" => "",
            "SubBuilding" => "",
            "PostalCode" => $postcode,
            "Organization" => "",
            "PostBox" => ""
        ];
        $result = $verify->verifyAddress(['Addresses' => [$params]]);
        var_dump($result);
        break;
    case 'email_verify':
        $email = (string)readline('Enter email address: ');
        $result = $verify->verifyEmail(['Email' => $email]);
        var_dump($result);
        break;
    case 'phone_verify':
        $phone = (string)readline('Enter phone number: ');
        $result = $verify->verifyPhone(['Phone' => $phone]);
        var_dump($result);
        break;
    default:
        echo 'Unknown command';
}
