<?php
filter_input(INPUT_GET, $start, FILTER_SANITIZE_SPECIAL_CHARS);    
filter_input(INPUT_GET, $woeid, FILTER_SANITIZE_SPECIAL_CHARS);    

function getlocation($text){
  $yql = 'select * from geo.places where text="'.$text.'"';
  $yqlendpoint = 'http://query.yahooapis.com/v1/public/yql?format=json';
  $query = $yqlendpoint.'&q='.urlencode($yql);
  $data = get($query);
  return json_decode($data);
}

function getdetails($woeid){
  // Burn, YQL, burn! (I cannot believe that this works :))
  $yql= 'select * from query.multi where queries = "'.
    'select * from geo.places where woeid = '.$woeid.';'.
    'select * from geo.places.ancestors where descendant_woeid = '.$woeid.';'.
    'select * from geo.places.belongtos where member_woeid = '.$woeid.';'.
    'select * from geo.places.children where parent_woeid = '.$woeid.';'.
    'select * from geo.places.neighbors where neighbor_woeid = '.$woeid.';'.
    'select * from geo.places.parent where child_woeid = '.$woeid.';'.
    'select * from geo.places.siblings where sibling_woeid = '.$woeid.'"';

  $yqlendpoint = 'http://query.yahooapis.com/v1/public/yql?format=json';
  $query = $yqlendpoint.'&q='.urlencode($yql).
          '&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
  $data = get($query);
  return json_decode($data);
}

function renderlist($p){
  if(sizeof($p)>1){
    foreach($p as $pp){
      $o .= render($pp);
    }
  } else{
    $o = render($p);
  }
  return $o;
}

function render($set){
  $out = '<li><a href="index.php?woeid='.$set->woeid.'">'.$set->name.
         '</a> <span>('.$set->placeTypeName->content.')</span>'.
         ':<ul><li>Country: '.$set->country->content.'</li>';

          if($set->admin1){
           $out .= '<li>Administrative:<ul>';
           if($set->admin1){
             $out .= '<li>'.$set->admin1->content.
                     ' <span>('.$set->admin1->type.')</span></li>';
           }
           if($set->admin2){
             $out .= '<li>'.$set->admin2->content.
                     ' <span>('.$set->admin2->type.')</span></li>';
           }
           if($set->admin3){
             $out .= '<li>'.$set->admin3->content.
                     ' <span>('.$set->admin3->type.')</span></li>';
           }
           $out .= '</ul></li>';
         }
         if($set->locality1){
           $out .= '<li>Localities:<ul>';
           if($set->locality1){
             $out .= '<li>'.$set->locality1->content.
                     ' <span>('.$set->locality1->type.')</span></li>';
           }
           if($set->locality2){
             $out .= '<li>'.$set->locality2->content.
                     ' <span>('.$set->locality2->type.')</span></li>';
           }
           if($set->locality3){
             $out .= '<li>'.$set->locality3->content.
                     ' <span>('.$set->locality3->type.')</span></li>';
           }
           $out .= '</ul></li>';
         }
         if($set->postal){
           $out .= '<li>Postal '.$set->postal->content.
                   ' <span>('.$set->postal->type.')</span></li>';
         }
         $lat = $set->centroid->latitude;
         $lon = $set->centroid->longitude;
         $nelat = $set->boundingBox->northEast->latitude;
         $nelon = $set->boundingBox->northEast->longitude;
         $swlat = $set->boundingBox->southWest->latitude;
         $swlon = $set->boundingBox->southWest->longitude;
         $out .= '<li>Location (lat/lon): <a href="'.
                 'http://maps.yahoo.com/map?ard=1&lat='.$lat.'&lon='.$lon.
                 '" class="latlon">'.$lat.', '.$lon.'</a></li>'.
                 '<li>Bounding Box:<p>NE <a href="http://maps.yahoo.com/map'.
                 '?ard=1&lat='.$nelat.'&lon='.$nelon.'" class="latlon">'.
                 $nelat.', '.$nelon.'</a></p>'.
                 '<p>SW <a href="http://maps.yahoo.com/map?ard=1&lat='.$swlat.
                 '&lon='.$swlon.'" class="latlon">'.$swlat.', '.$swlon.
                 '</a></p>'.
                 '</li>'.
          '</ul></li>';
  return $out;
}
function get($url){
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $output = curl_exec($ch); 
  curl_close($ch);
  return $output;
}
?>
