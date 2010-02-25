<?php 
  $mapskey = 'SckyCn7V34EmWovT9sK7Z2ivzd2NtNKOMzTWVFwViDtkNL'.
             'vxNKbiLqqWN9nqtdg-';
  $staticmapskey = '0j2VBAXV34G7YDIVuz0OUlf1lQxmS0L5gO_'.
                   'cLFiIv8ZtHjpgxYCUO1TfLn2MVG8-';             
  // ^ get your own codes @ https://developer.apps.yahoo.com/wsregapp/

  include('geoexplorer.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>GeoPlanet Explorer</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
  <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
<div id="doc" class="yui-t7">
  <div id="hd" role="banner">
    <h1><span>GeoPlanet</span> Explorer</h1>
  </div>
  <div id="bd" role="main">
    
    <form id="f" action="index.php">
      <div>
        <label for="start">Find a location:</label>
        <input type="text" id="start" name="start">
        <input type="submit" value="find">
      </div>
    </form>
    <div id="results">

      <div id="info">

      <?php if(!isset($_GET['start']) && !isset($_GET['woeid'])){?>

        <p>Welcome to the GeoPlanet Explorer. 
          Here you can explore the geographical information provided by Yahoo in the <a href="http://developer.yahoo.com/geo/geoplanet/">GeoPlanet API and data set</a>.</p>
        <p>Simply enter a location in the form above and submit it to get detailed information about the place you are looking for - including its ancestors, siblings, children and other relationships.</p>
      <?php }?>

      <?php if(isset($_GET['woeid'])){?>
        <p>Here's the information about the location you requested. Click any of the links to reload this page to learn more about this location.</p>
        <p id="latlong">Clicking any of the links with a lat/lon pair will take you to Yahoo maps.</p>
      <?PHP } ?>
      </div>

      <?php if(isset($_GET['start'])){
        $all = getlocation($_GET['start']);
        if($all->query->results){
          echo '<h2>We found these locations, which one do you want?</h2>';
          echo '<ul>';
          if(sizeof($all->query->results->place)>1){
            foreach($all->query->results->place as $p){
              echo render($p);
            }
          } else {
            echo render($all->query->results->place);
          }
          echo '</ul>';
        } else {
          echo '<h2>Nothing to see here...</h2>';
          echo '<p>Sorry but I was unable to find a place of that name.</p>';
        }
      }?>
      
      <?php if(isset($_GET['woeid'])){
        $all = getdetails($_GET['woeid']);
        if($all->query->results){
          
          echo '<div class="yui-gd">'; 
          echo '<div class="yui-u first">';
          
          echo '<h2>Place info</h2>';
          echo '<ul id="placeinfo">';
          
          echo renderlist($all->query->results->results[0]->place);
          $current = $all->query->results->results[0]->place;
          $name = $current->name;
          $lat = $current->centroid->latitude;
          $lon = $current->centroid->longitude;
          $url= 'http://local.yahooapis.com/MapsService/V1/mapImage?'.
                'appid='.$staticmapskey.
                '&image_height=270&image_width=470&output=php'.
                '&latitude='.$lat.'&longitude='.$lon;
          $img = unserialize(get($url));

          echo '</ul>';
          
          echo '</div>';
          
          echo '<div class="yui-u"><div id="map">'.
               '<a href="http://maps.yahoo.com/map'.
               '?ard=1&lat='.$lat.'&lon='.$lon.'">On Yahoo Maps</a>'.
               '<img src="'.$img['Result'].'" alt="map of '.$name.'"></div>';
          
          echo '</div>';
          echo '</div>';
          

          echo '<div class="yui-gb">'; 
          echo '<div class="yui-u first">';
          echo '<h3>Parent:</h3>';
          if($all->query->results->results[5]){
            echo '<ul class="collapse">';
            echo renderlist($all->query->results->results[5]->place);
            echo '</ul>';
          }
          echo '</div>';
          echo '<div class="yui-u">';

          echo '<h3>Ancestors</h3>';
          if($all->query->results->results[1]){
            echo '<ul class="collapse">';
            echo renderlist($all->query->results->results[1]->place);
            echo '</ul>';
          }
          echo '</div>';

          echo '<div class="yui-u">';
          echo '<h3>Belongs to:</h3>';
          if($all->query->results->results[2]){
            echo '<ul class="collapse">';
            echo renderlist($all->query->results->results[2]->place);
            echo '</ul>';
          }
          echo '</div>';
          echo '</div>';

          echo '<div class="yui-gb">'; 
          echo '<div class="yui-u first">';
          echo '<h3>Children:</h3>';
          if($all->query->results->results[3]){
            echo '<ul class="collapse">';
            echo renderlist($all->query->results->results[3]->place);
            echo '</ul>';
          }
          echo '</div>';

          echo '<div class="yui-u">';
          echo '<h3>Neighbours:</h3>';
          if($all->query->results->results[4]){
            echo '<ul class="collapse">';
            echo renderlist($all->query->results->results[4]->place);
            echo '</ul>';
          }
          echo '</div>';

          echo '<div class="yui-u">';
          echo '<h3>Siblings:</h3>';
          if($all->query->results->results[6]){
            echo '<ul class="collapse">';
            echo renderlist($all->query->results->results[6]->place);
            echo '</ul>';
          }
          echo '</div>';
          echo '</div>';

        }
      }?>
    </div>

  </div>
<div id="ft" role="contentinfo"><p>Written by <a href="http://wait-till-i.com">Chris Heilmann</a>, powered by <a href="http://developer.yahoo.com/yql/">YQL</a> and <a href="http://developer.yahoo.com/geo">GeoPlanet</a>.</p></div>
</div>
<script src="http://yui.yahooapis.com/3.0.0/build/yui/yui-min.js"></script>
<script src="geoexplorer.js"></script>
<script type="text/javascript"
src="http://api.maps.yahoo.com/ajaxymap?v=3.8&appid=<?php echo $mapskey;?>"></script>
<script type="text/javascript">
  document.body.className = 'js';
	if(document.getElementById('map')){
	var map = new YMap(document.getElementById('map'));
  	map.addTypeControl();
  	map.addZoomLong();
  	map.addPanControl();
  	map.setMapType(YAHOO_MAP_REG);
  	map.drawZoomAndCenter("<?php echo $lat.','.$lon;?>");
	}
</script>
</body>
</html>