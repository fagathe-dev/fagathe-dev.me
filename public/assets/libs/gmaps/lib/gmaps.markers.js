GMaps.prototype.createMarker=function(e){if(null==e.lat&&null==e.lng&&null==e.position)throw"No latitude or longitude defined.";var r=this,t=e.details,n=e.fences,a=e.outside,i={position:new google.maps.LatLng(e.lat,e.lng),map:null},o=extend_object(i,e);delete o.lat,delete o.lng,delete o.fences,delete o.outside;var s=new google.maps.Marker(o);if(s.fences=n,e.infoWindow){s.infoWindow=new google.maps.InfoWindow(e.infoWindow);for(var l=["closeclick","content_changed","domready","position_changed","zindex_changed"],d=0;d<l.length;d++)!function(r,t){e.infoWindow[t]&&google.maps.event.addListener(r,t,(function(r){e.infoWindow[t].apply(this,[r])}))}(s.infoWindow,l[d])}var h=["animation_changed","clickable_changed","cursor_changed","draggable_changed","flat_changed","icon_changed","position_changed","shadow_changed","shape_changed","title_changed","visible_changed","zindex_changed"],c=["dblclick","drag","dragend","dragstart","mousedown","mouseout","mouseover","mouseup"];for(d=0;d<h.length;d++)!function(r,t){e[t]&&google.maps.event.addListener(r,t,(function(){e[t].apply(this,[this])}))}(s,h[d]);for(d=0;d<c.length;d++)!function(r,t,n){e[n]&&google.maps.event.addListener(t,n,(function(t){t.pixel||(t.pixel=r.getProjection().fromLatLngToPoint(t.latLng)),e[n].apply(this,[t])}))}(this.map,s,c[d]);return google.maps.event.addListener(s,"click",(function(){this.details=t,e.click&&e.click.apply(this,[this]),s.infoWindow&&(r.hideInfoWindows(),s.infoWindow.open(r.map,s))})),google.maps.event.addListener(s,"rightclick",(function(t){t.marker=this,e.rightclick&&e.rightclick.apply(this,[t]),null!=window.context_menu[r.el.id].marker&&r.buildContextMenu("marker",t)})),s.fences&&google.maps.event.addListener(s,"dragend",(function(){r.checkMarkerGeofence(s,(function(e,r){a(e,r)}))})),s},GMaps.prototype.addMarker=function(e){var r;if(e.hasOwnProperty("gm_accessors_"))r=e;else{if(!(e.hasOwnProperty("lat")&&e.hasOwnProperty("lng")||e.position))throw"No latitude or longitude defined.";r=this.createMarker(e)}return r.setMap(this.map),this.markerClusterer&&this.markerClusterer.addMarker(r),this.markers.push(r),GMaps.fire("marker_added",r,this),r},GMaps.prototype.addMarkers=function(e){for(var r,t=0;r=e[t];t++)this.addMarker(r);return this.markers},GMaps.prototype.hideInfoWindows=function(){for(var e,r=0;e=this.markers[r];r++)e.infoWindow&&e.infoWindow.close()},GMaps.prototype.removeMarker=function(e){for(var r=0;r<this.markers.length;r++)if(this.markers[r]===e){this.markers[r].setMap(null),this.markers.splice(r,1),this.markerClusterer&&this.markerClusterer.removeMarker(e),GMaps.fire("marker_removed",e,this);break}return e},GMaps.prototype.removeMarkers=function(e){var r=[];if(void 0===e){for(var t=0;t<this.markers.length;t++){(a=this.markers[t]).setMap(null),GMaps.fire("marker_removed",a,this)}this.markerClusterer&&this.markerClusterer.clearMarkers&&this.markerClusterer.clearMarkers(),this.markers=r}else{for(t=0;t<e.length;t++){var n=this.markers.indexOf(e[t]);if(n>-1)(a=this.markers[n]).setMap(null),this.markerClusterer&&this.markerClusterer.removeMarker(a),GMaps.fire("marker_removed",a,this)}for(t=0;t<this.markers.length;t++){var a;null!=(a=this.markers[t]).getMap()&&r.push(a)}this.markers=r}};