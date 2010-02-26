YUI().use('node','event','io-base',function(Y) {
  Y.on('domready', function(e){
	 Y.one('#latlong').set('innerHTML',
		'Clicking any of the links with a lat/lon pair will refresh the map'+
		' and show you the location. Clicking the "+" links shows and hides '+
		'the details of every location.'
	 ); 
	 Y.all('ul.collapse ul').each(function(o){
	   if((o.ancestor().ancestor().hasClass('collapse'))){
	     o.ancestor().prepend('<a href="show" class="toggle">+</a>',o);
	   }
	 });
	 Y.one('#bd').delegate('click',function(e){
	   if(this.hasClass('toggle')){
	     this.ancestor().toggleClass('open');
	     if(this.ancestor().hasClass('open')){
	       this.set('innerHTML','-');
	       this.set('href','close');
	     } else {
	       this.set('innerHTML','+');
	       this.set('href','open');
	     }
	     e.preventDefault();
	   }
	   if(this.hasClass('latlon')){
	     if(map){
	       e.preventDefault();
					var points = [];
					var content = this.get('innerHTML').split(', ');
      		var point = new YGeoPoint(content[0],content[1]);
					points.push(point);
					var newMarker = new YMarker(point);
		      map.addOverlay(newMarker);
					var bbinfo = this.get('className').replace('latlon bb','');
					var bbpoints = bbinfo.split('x');
					var bb1 = bbpoints[0].split('/');
      		var point = new YGeoPoint(bb1[0],bb1[1]);
					points.push(point);
					var bb1 = bbpoints[1].split('/');
      		var point = new YGeoPoint(bb1[0],bb1[1]);
					points.push(point);
					map.disableKeyControls();
			    var zac = map.getBestZoomAndCenter(points);
			    map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);
	     }
	   }
	 },'a');
  });
});
