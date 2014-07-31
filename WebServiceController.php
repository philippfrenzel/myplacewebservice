<?php

require 'vendor/autoload.php';
include 'XmlImporter.php';

use \SimpleXMLElement;
use GuzzleHttp\Client;

$ServiceUrl = "http://192.168.99.55/Crminterface/xml";

/* This is just a dummy record, that will be send */
$result['id'] = '123456';
$result['datum'] = '14.02.2014';
$result['groesse'] = '2.00';
$result['preis'] = '14.56';
$result['land'] = 'DE';
$result['bundesland'] = 'BW';
$result['filiale'] = 'Langenzersdorf';
$result['filial_ID'] = '010';
$result['plz'] = '1010';
$result['ort'] = 'Wien';
$result['kunde'] = 'Nein';
$result['kunde_vorname'] = 'Philipp';
$result['kunde_nachname'] = 'Frenzel';
$result['kunde_geschlecht'] = 'm';
$result['email'] = htmlentities('philipp.frenzel@myplace.eu');
$result['txt'] = htmlentities('Würde das Abteil gerne ab Mitte März mieten');
$result['sonderangebot'] = 'Gratis Wochen';
$result['rueckruf'] = 'Ja';
$result['tel'] = '0043 699 19089393';

header('Content-type: application/xml');    
$rawxml = new SimpleXMLElement('<SendWebrequest></SendWebrequest>');
$xml = myplace\XmlImporter::toXML($result, 'SendWebrequest',$rawxml);

$ServiceUrl = htmlentities($ServiceUrl); //changed this to html_entity decode
$client = new Client();
$request = $client->post($ServiceUrl,[
  'headers' => [
      'Content-Type' => 'text/xml; charset=UTF8'
  ],
  'body' => $xml,
  'timeout' => 120
]);

$response = $request->send();
echo $response->getBody();
exit;

?>