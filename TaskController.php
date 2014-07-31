<?php

require 'vendor/autoload.php';
include 'XmlImporter.php';

use \SimpleXMLElement;
use GuzzleHttp\Client;

$ServiceUrl = "http://192.168.99.55/Crminterface/xml";

/* This is just a dummy record, that will be send */
/*
<request _action="Erinnerung">
  <import dateformatin="ymd" extsystem="RentPlus">
     <fields>
       <Tasks>
         <Rep extkey="true">[à Kostenstellenkürzel 3 stellig]</Rep>
         <Priority>[à niedrig | hoc | mittel]</Priority>
         <Subject>Check Move-Out</Subject>
         <Text>Check MO (Abteil 1234, Kunde XY)"</Text>
         <DueDate>[JJJJMMTT]</DueDate>
         <DueTime>[hh:mm[</DueTime>
         <Tasktype>Erinnerung</Tasktype>
         <Taskstatus>Unbearbeitet</Taskstatus>
       </Tasks>
     </fields>
  </import>
</request>
*/

$request = new SimpleXMLElement('<request></request>');
$request->addAttribute('_action','Erinnerung');
$import = $request->addChild('import');
$import->addAttribute('dateformatin','ymd');
$import->addAttribute('extsystem','RentPlus');
$fields = $import->addChild('fields');
$task = $fields->addChild('Tasks');

$kostenstelle = $task->addChild('Rep','018');
$kostenstelle->addAttribute('extkey','true');

$task->addChild('Priority','mittel');
$task->addChild('Subject','Check Move-Out');
$task->addChild('Text','Check MO (Abteil 1234, Kunde XY)');
$task->addChild('DueDate','20140801');
$task->addChild('DueTime','08:00');
$task->addChild('Tasktype','Erinnerung');
$task->addChild('Taskstatus','Unbearbeitet');

header('Content-type: application/xml');    

//$rawxml = new SimpleXMLElement('<request action="_Erinnerung"><import dateformatin="ymd" extsystem="RentPlus"><fields><tasks></tasks></fields></import></request>');
//$xml = myplace\XmlImporter::toXML($tasks, 'tasks',$rawxml);

$xml = $request->asXML(); //exit;

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