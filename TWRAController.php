<?php
require_once('config.php');


class TWRAController
{


	function sunrisesunsetNearest($lat,$long,$year,$month,$day)
	{				
		$i=0;		
		$sql="select city_uid,latitude,longitude from twra_cities";
		$res=mysql_query($sql);			
		
		$min = 0;
		$cityUid=0;
		while($row=mysql_fetch_array($res))
		{								
			if($row[1]==$lat && $row[2]==$long)
			{
				$cityUid = $row[0];
				break;
			}
			else
			{				
				$lat1=$row[1];
				$lng1=$row[2];				
				$lat2=$lat;
				$lng2=$long;
				$dist=$this->distance($lat1, $lng1, $lat2, $lng2, $miles = true);
				//echo $dist;
				//echo "<br>";
				if($i == 0) 
				{
					$min = $dist;					
				}
				else 
				{ 
					if($dist < $min) 
					{					
						$min = $dist;						
						$cityUid = $row[0];
						
					}
				}
				$i++;
						
			}
	
		}		
		$sql1="select sunrise_time,sunset_time from twra_sun_rise_set_times where city_uid=".$cityUid." and year=".$year." and month=".$month." and day=".$day;
		$sunrise='';
		$sunset='';
		$city='';		
		$res1=mysql_query($sql1);
		while($row1=mysql_fetch_array($res1))
		{
			$sunrise=$row1[0];
			$sunset=$row1[1];
		}		
		$sql2="select City from twra_cities where city_uid=".$cityUid;		
		$res2=mysql_query($sql2);		
		while($row2=mysql_fetch_array($res2))
		{			
			$city=$row2[0];
			
		}		
		return array("sunRise"=>$sunrise,"sunSet"=>$sunset,"cityname"=>$city);
	}
	
	function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
	{
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;
		
		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;
		
		return ($miles ? ($km * 0.621371192) : $km);
	}


	function getLocations($type)
	{
	$result = array();
	
	if($type=="wma")
	{
		$sql = "SELECT * FROM wma_app ";
		$res = mysql_query($sql);
		$i =0;
		while($row = mysql_fetch_array($res))
		{
			$result[$i]['identifier'] = $row['id'] ;
			$result[$i]['name'] = $row['name'] ;
			$result[$i]['latitude'] = $row['latitude'] ;
			$result[$i]['longitude'] = $row['longitude'] ;
			$result[$i]['info'] = ucfirst(strtolower($row['county_name'])).", Region ".$row['region'];
			$i++;
		}
	}
	
	else if($type=="check_stations")
	{
		$sql = "SELECT * FROM checkstations ";
		$res = mysql_query($sql);
		$result = array();
		$i =0;
		while($row = mysql_fetch_array($res))
		{
			$result[$i]['identifier'] = $row['id'] ;
			$result[$i]['name'] = $row['business_name'] ;
			$result[$i]['latitude'] = $row['latitude'] ;
			$result[$i]['longitude'] = $row['longitude'] ;
			$result[$i]['info'] = ucfirst(strtolower($row['city']));
			$i++;
		}
	
	}
	
	else if($type=="processors")
	{
		$sql = "SELECT * FROM hfth_processors ";
		$res = mysql_query($sql);
		$result = array();
		$i =0;
		while($row = mysql_fetch_array($res))
		{
			$result[$i]['identifier'] = $row['id'] ;
			$result[$i]['name'] = $row['name'] ;
			$result[$i]['latitude'] = $row['latitude'] ;
			$result[$i]['longitude'] = $row['longitude'] ;
			$result[$i]['info'] = ucfirst(strtolower($row['city']));
			$i++;
		}
	}
	
	else if($type=="boat_ramps")
	{
		$sql = "SELECT * FROM boat_boatlaunchsites ";
		$res = mysql_query($sql);
		$result = array();
		$i =0;
		while($row = mysql_fetch_array($res))
		{
			$result[$i]['identifier'] = $row['id'] ;
			$result[$i]['name'] = $row['name'] ;
			$result[$i]['latitude'] = $row['latitude'] ;
			$result[$i]['longitude'] = $row['longitude'] ;
			$result[$i]['info'] = ucfirst(strtolower($row['waterway']));
			$i++;
		}
	}
	
	
	else if($type=="places_to_watch")
	{
		$sql = "SELECT * FROM twrawheretowatch ";
		$res = mysql_query($sql);
		$result = array();
		$i =0;
		while($row = mysql_fetch_array($res))
		{
			$result[$i]['identifier'] = $row['id'] ;
			$result[$i]['name'] = $row['statearea'] ;
			$result[$i]['latitude'] = $row['latitude'] ;
			$result[$i]['longitude'] = $row['longitude'] ;
			$result[$i]['info'] =  $row['region'] ;
			$i++;
		}
	}
	
	
	else if($type=="places_to_fish")	
	{
		$sql = "SELECT * FROM fish_wateraccesssites ";
		$res = mysql_query($sql);
		$result = array();
		$i =0;
		while($row = mysql_fetch_array($res))
		{
			$result[$i]['identifier'] = $row['id'] ;
			$result[$i]['name'] = $row['name'] ;
			$result[$i]['latitude'] = $row['latitude'] ;
			$result[$i]['longitude'] = $row['longitude'] ;
			$result[$i]['info'] = ucfirst(strtolower($row['waterway']));// strtolower( 'Hours Open : '.$row['hours_open'].', Waterway : '.$row['waterway']) ;
			$i++;
		}
		
	}
		
	return $result;
	
	
	
	}
	
	
	function getLocationDetails($type,$locationID)
	{
	
	if($type=="wma")
	{
	 $sql="SELECT * FROM wma_app WHERE id='$locationID'";
   		if($res=mysql_query($sql))
	 	$row=mysql_fetch_assoc($res);
	}
	
	else if($type=="check_stations")
	{
		$sql = "SELECT phone,CONCAT(street,' , ',city,' , ',state,' , ',zip) AS address FROM checkstations WHERE id='$locationID'";
		if($res=mysql_query($sql))
	 	$row=mysql_fetch_assoc($res);	
	}	
	else if($type=="processors")
	{
		 $sql = "SELECT phone,CONCAT(street,' , ',city,' , ',state,' , ',zone) AS address FROM hfth_processors WHERE id='$locationID'";
		if($res=mysql_query($sql))
	 	$row=mysql_fetch_assoc($res);
	}	
	else if($type=="boat_ramps")
	{
		$sql = "SELECT * FROM boat_boatlaunchsites WHERE id='$locationID'";
		if($res=mysql_query($sql))
	 	$row=mysql_fetch_assoc($res);
	}
	else if($type=="places_to_watch")
	{
		$sql = "SELECT * FROM twrawheretowatch WHERE id='$locationID'";
			if($res=mysql_query($sql))
	 	$row=mysql_fetch_assoc($res);
	
	}
	else if($type=="places_to_fish")	
	{		
		$sql = "SELECT * FROM fish_wateraccesssites WHERE id='$locationID'";
			if($res=mysql_query($sql))
	 	$row=mysql_fetch_assoc($res);
	
	}
	
	return $row;
	}
    
    
    
    
	function getLocationsBasedOnSearch($type,$detail)
	{
        $result = array();
        //global $detail;
        if($type=="wma")
        {
            
            
             $sql = "SELECT wma_app.*,search.distance FROM `citysearch_wma` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN wma_app ON wma_app.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
            
            //$sql = "SELECT * FROM wma_app where name LIKE '%".$detail."%'  OR twra_manager  LIKE '%".$detail."%' OR area_description  LIKE '%".$detail."%'  OR county_name  LIKE '%".$detail."%' OR  region  LIKE '%".$detail."%'";
            $res = mysql_query($sql);
            $i =0;
            while($row = mysql_fetch_array($res))
            {
                $result[$i]['identifier'] = $row['id'] ;
                $result[$i]['name'] = $row['name'] ;
                $result[$i]['latitude'] = $row['latitude'] ;
                $result[$i]['longitude'] = $row['longitude'] ;
                $result[$i]['distance']=$row['distance'];
                $result[$i]['info'] = ucfirst(strtolower($row['county_name'])).", Region ".$row['region'];
                $i++;
            }
        }
        
        else if($type=="check_stations")
        {
            
            $sql = "SELECT checkstations.*,search.distance FROM `citysearch_checkstations` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN checkstations ON checkstations.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
            
            
            
            //	$sql = "SELECT * FROM checkstations where business_name LIKE '%".$detail."%' OR city LIKE '%".$detail."%' OR state LIKE '%".$detail."%' OR zip LIKE '%".$detail."%'";
            $res = mysql_query($sql);
            $result = array();
            $i =0;
            while($row = mysql_fetch_array($res))
            {
                $result[$i]['identifier'] = $row['id'] ;
                $result[$i]['name'] = $row['business_name'] ;
                $result[$i]['latitude'] = $row['latitude'] ;
                $result[$i]['longitude'] = $row['longitude'] ;
                $result[$i]['distance']=$row['distance'];
                $result[$i]['info'] = ucfirst(strtolower($row['city']));
                $i++;
            }
            
        }
        
        else if($type=="processors")
        {
           /* 
            $sql = "SELECT hfth_processors_sv.*,search.distance FROM `citysearch_processors_sv` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN hfth_processors_sv ON hfth_processors_sv.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
*/
            $sql = "SELECT hfth_processors.*,search.distance FROM `citysearch_processors` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN hfth_processors ON hfth_processors.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
            //$sql = "SELECT * FROM hfth_processors where name LIKE '%".$detail."%' OR city LIKE '%".$detail."%' OR state LIKE '%".$detail."%' OR zone  LIKE '%".$detail."%'";
            $res = mysql_query($sql);
            $result = array();
            $i =0;
            while($row = mysql_fetch_array($res))
            {
                $result[$i]['identifier'] = $row['id'] ;
                $result[$i]['name'] = $row['name'] ;
                $result[$i]['latitude'] = $row['latitude'] ;
                $result[$i]['longitude'] = $row['longitude'] ;
                $result[$i]['distance'] = $row['distance'] ;
                $result[$i]['info'] = ucfirst(strtolower($row['city']));
                $i++;
            }
        }
        
        else if($type=="boat_ramps")
        {
            
            $sql = "SELECT boat_boatlaunchsites.*,search.distance FROM `citysearch_boatlaunchsites` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN boat_boatlaunchsites ON boat_boatlaunchsites.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
            
            //$sql = "SELECT * FROM boat_boatlaunchsites where name LIKE '%".$detail."%' OR type  LIKE '%".$detail."%' OR waterway LIKE '%".$detail."%'";
            $res = mysql_query($sql);
            $result = array();
            $i =0;
            while($row = mysql_fetch_array($res))
            {
                $result[$i]['identifier'] = $row['id'] ;
                $result[$i]['name'] = $row['name'] ;
                $result[$i]['latitude'] = $row['latitude'] ;
                $result[$i]['longitude'] = $row['longitude'] ;
                $result[$i]['distance'] = $row['distance'] ;
                $result[$i]['info'] = ucfirst(strtolower($row['waterway']));
                $i++;
            }
        }
        
        
        else if($type=="places_to_watch")
        {
            
            $sql = "SELECT twrawheretowatch.*,search.distance FROM `citysearch_wheretowatch` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN twrawheretowatch ON twrawheretowatch.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
            
            //$sql = "SELECT * FROM twrawheretowatch where region  LIKE '%".$detail."%' OR statearea LIKE '%".$detail."%'  ";
            $res = mysql_query($sql);
            $result = array();
            $i =0;
            while($row = mysql_fetch_array($res))
            {
                $result[$i]['identifier'] = $row['id'] ;
                $result[$i]['name'] = $row['statearea'] ;
                $result[$i]['latitude'] = $row['latitude'] ;
                $result[$i]['longitude'] = $row['longitude'] ;
                $result[$i]['distance'] = $row['distance'] ;
                $result[$i]['info'] = $row['region'] ;
                $i++;
            }
        }
        
        
        else if($type=="places_to_fish")
        {
            
            $sql = "SELECT fish_wateraccesssites.*,search.distance FROM `citysearch_wateraccesssites` as  search
            JOIN cities as c ON  c.city_id=search.city_id
            JOIN fish_wateraccesssites ON fish_wateraccesssites.id=search.location_id
            WHERE c.city_name LIKE '%".$detail."%' ORDER BY search.distance
            LIMIT 20";
            
            //	$sql = "SELECT * FROM fish_wateraccesssites where name LIKE '%".$detail."%' OR waterway LIKE '%".$detail."%' OR type  LIKE '%".$detail."%'";
            $res = mysql_query($sql);
            $result = array();
            $i =0;
            while($row = mysql_fetch_array($res))
            {
                $result[$i]['identifier'] = $row['id'] ;
                $result[$i]['name'] = $row['name'] ;
                $result[$i]['latitude'] = $row['latitude'] ;
                $result[$i]['longitude'] = $row['longitude'] ;
                $result[$i]['distance'] = $row['distance'] ;
                $result[$i]['info'] = ucfirst(strtolower($row['waterway']));// strtolower( 'Hours Open : '.$row['hours_open'].', Waterway : '.$row['waterway']) ;
                $i++;
            }
            
        }
		
        return $result;
        
        
        
	}
	
	function getRecipes()
	{
        $result = array();
        $sql = "SELECT * FROM recipes";
		$res = mysql_query($sql);
		$i =0;
		while($row = mysql_fetch_assoc($res))
		{
           $row = array_map('utf8_encode', $row);
            
            
			$result[$i]=$row;
			$i++;
		}
        return $result;
        
	}


    
    
    function getFishGuide()
	{
        $result = array();
        $sql = "SELECT * FROM fish_id_guide";
		$res = mysql_query($sql);
		$i =0;
		while($row = mysql_fetch_assoc($res))
		{
            $row = array_map('utf8_encode', $row);
            
			$result[$i]=$row;
			$i++;
		}
        return $result;
        
	}
    
    
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
		$dom = new DOMDocument();
		$dom->recover = true;
        libxml_use_internal_errors(true);
		$dom->validateOnParse = true;
		$dom->loadHTML($data);
		$tables = $dom->getElementsByTagName('table');

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
		
		 $sunrise= trim(substr($sunrise,strpos($sunrise,"Sunrise")+7,50));
		 $sunset= trim(substr($sunset,strpos($sunset,"Sunset")+6,50));
        if(!isset($sunrise))
            return getSunriseSunsetTimings("TN","Nashville",$day,$month,$year);
        
		return $sunrise."&&&".$sunset;
        
    }
   
    
    

}



?>
