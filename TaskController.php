<?php

require 'vendor/autoload.php';
include 'XmlImporter.php';

use \SimpleXMLElement;
use GuzzleHttp\Client;

$ServiceUrl = "http://81.223.114.170:62080/Crminterface/xml";

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
$task->addChild('Subject','Move Out: Birringer');
$task->addChild('Text','Check MO (Abteil 4711, Kunde Philipp Frenzel)');
$task->addChild('DueDate','20140801');
$task->addChild('DueTime','14:40');
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

//$response = $request->send();
echo $request->getBody();
exit;

?>