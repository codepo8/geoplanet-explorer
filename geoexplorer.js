YUI().use('node','event','io-base',function(Y) {
  Y.on('domready', function(e){
	 Y.one('#latlong').set('innerHTML',
		'Clicking any of the links with a lat/lon pair will refresh the map'+
		'and show you the location. Clicking the "+" links shows and hides '+
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
	       map.drawZoomAndCenter(this.get('innerHTML'));
	     }
	   }
	 },'a');
  });
});
