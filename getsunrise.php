<?php
/*
$data = <<<HTML
<table id="tableA">
            <tr class="trA"><td class="tdAleft">Wednesday, October 7, 2015</td>
            <td class="tdAright">Central Daylight Time</td></tr></table><table>
          <tr style="font-size:20px">
          <th colspan="2">Sun</th>
          </tr>
            <tr><td>Begin civil twilight</td><td>6:34 a.m.</td></tr>
            <tr><td>Sunrise</td><td>6:59 a.m.</td></tr>
            <tr><td>Sun transit</td><td>12:48 p.m.</td></tr>
            <tr><td>Sunset</td><td>6:36 p.m.</td></tr>
            <tr><td>End civil twilight</td><td>7:01 p.m.</td></tr><tr style="font-size:20px"><th colspan="2">Moon</th></tr>
               <tr><td>Moonrise</td><td>2:11 a.m.</td></tr>
               <tr><td>Moon transit</td><td>9:02 a.m.</td></tr>
               <tr><td>Moonset</td><td>3:48 p.m.</td></tr></table>
HTML;

$xml = new DOMDocument();
$xml->validateOnParse = true;
$xml->loadHTML($data);
$tables = $xml->getElementsByTagName('table');

// Find the correct <table> element you want, and store it in $table
// ...
// Assume you want the first table
$table = $tables->item(1);

foreach ($table->childNodes as $td) {
  if (strpos($td->nodeValue, 'Sunrise') !== FALSE)
  
  {
	  $sunrise=$td->nodeValue;
  }
 if (strpos($td->nodeValue, 'Sunset') !== FALSE)
  {
	  $sunset=$td->nodeValue;
  }

  
}
 $sunrise= trim(substr($sunrise,strpos($str,"Sunrise")+7,50));
 $sunset= trim(substr($sunset,strpos($str,"Sunset")+6,50));

echo $sunrise."&&&".$sunset;*/



 getSunriseSunsetTimings('TN','Memphis',6,10,2015);
 function getSunriseSunsetTimings($state,$place,$day,$month,$year){
 $fields_string="";
        //set POST variables
		/**** Older format   
					  $url = 'http://aa.usno.navy.mil/cgi-bin/aa_pap.pl';
					 $fields = array(

						'FFX' => "1",
						'ID' =>"AA",
						'xxy' => urlencode($year),
						'xxm' => urlencode($month),
						'xxd' => urlencode($day),
						'st' => urlencode($state),
						'place' =>urlencode($place),
						'ZZZ'=>"END"
						);
		****/
        $url = 'http://aa.usno.navy.mil/rstt/onedaytable?';
        $fields = array(
						'ID' =>"AA",
						'year' => urlencode($year),
						'month' => urlencode($month),
						'day' => urlencode($day),
						'state' => urlencode($state),
						'place' =>urlencode($place)
                        );
        
        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        $newstring = rtrim($fields_string, '&');
    	$newurl = $url.$newstring;
        $data = file_get_contents($newurl);
$xml = new DOMDocument();
$xml->validateOnParse = true;
$xml->loadHTML($data);
$tables = $xml->getElementsByTagName('table');

// Find the correct <table> element you want, and store it in $table
// ...
// Assume you want the first table
$table = $tables->item(1);

foreach ($table->childNodes as $td) {
  if (strpos($td->nodeValue, 'Sunrise') !== FALSE)
  
  {
   $sunrise=$td->nodeValue;
  }
 if (strpos($td->nodeValue, 'Sunset') !== FALSE)
  {
   $sunset=$td->nodeValue;
  }

  
}
 $sunrise= trim(substr($sunrise,strpos($str,"Sunrise")+7,50));
 $sunset= trim(substr($sunset,strpos($str,"Sunset")+6,50));

echo $sunrise."&&&".$sunset;
 }?>
        
