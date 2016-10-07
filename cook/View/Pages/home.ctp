<script type='text/javascript' src='http://code.jquery.com/jquery-1.7.1.js'></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://yui.yahooapis.com/3.14.1/build/yui/yui-min.js"></script>
<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>js/tooltip.js"></script> 
<script type='text/javascript'>
var lastOpenInfoWin = null;
$(function()
{
	var subjectRange;
	lat=geoip_latitude();
	lng=geoip_longitude();
    document.getElementById('UseraddressLattitude').value=lat;
    document.getElementById('UseraddressLongitude').value=lng;
    getAddress(lat, lng);
	var zoomval=setzoom();
    initialize(zoomval);
   // gettutorList();

	jQuery('#country').on('change', function(){
   		getlatlong($("#country option:selected").text());
   });

	function getlatlong(address)
	{
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
	
			if (status == google.maps.GeocoderStatus.OK)
			{
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				document.getElementById('UseraddressLattitude').value=latitude;
				document.getElementById('UseraddressLongitude').value=longitude;
				$('#UseraddressAddress').val('');
				zoomval=setzoom();
				initialize(zoomval);
			} 
		});
	   
	}

	function getAddress(lat, lng)
	{
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode({'latLng': latlng}, function(results, status)
		{
			if (status == google.maps.GeocoderStatus.OK)
			{
				console.log(results)
				if (results[1])
				{
					//formatted address
				   // alert(results[0].formatted_address)
					$("#UseraddressAddress").val(results[0].formatted_address);
					//find country name
					for (var i=0; i<results[0].address_components.length; i++)
					{
						for (var b=0;b<results[0].address_components[i].types.length;b++)
						{
							if (results[0].address_components[i].types[b] == "country")
							{
							//this is the object you are looking for
							country= results[0].address_components[i];
							break;
							}
						}
					}
					$("#country").val(country.short_name);
				}
				else
				{
					alert("No results found");
				}
			}
			else
			{
				alert("Geocoder failed due to: " + status);
			}
		});
	}

function initialize(zoomval)
{
	var lat=$('#UseraddressLattitude').val();
   	var lng=$('#UseraddressLongitude').val();
   	var myOptions = {
		zoom: zoomval,
  		center: latlng,
  		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var locations = [lat+','+lng];
	
	/**
	 * Draw points and check whether those points are inside a range from a point.
	*/
	var rad=$('#radius').val();
	if (rad=='')
	{
		rad=250;
	}
	//alert(rad);
	var subjectPoint = {
    point: new google.maps.LatLng(lat,lng),
    radius: parseInt(rad), //default radius
    color: '#00AA00'
	}

	var map;
	var geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(lat,lng);
	
	//render the range
	var subjectMarker = new google.maps.Marker({
		position: subjectPoint.point,
		title: 'Subject'
		
	});

	var subjectRange = new google.maps.Circle({
        map: map,
        radius: subjectPoint.radius,    // metres
        strokeColor: '#EA3800',
        fillOpacity: 0.0,
        strokeWeight:2,
        editable: false
    });
    subjectRange.bindTo('center', subjectMarker, 'position');

	function mapmarkers(lat,lng,icon,name,qual,distance,address,image, type)
	{
		if (type=='center')
		{
			map.setCenter(new google.maps.LatLng(lat, lng));
		}
		var marker = new google.maps.Marker({
			map: map, 
			position: new google.maps.LatLng(lat, lng),
			icon: icon,
			animation: google.maps.Animation.DROP
		});
		
		if (name!='' && qual!='')
		{
			distance=distance*1609.34;
			distance=Math.round(distance);
			if (image=='')
			{
				image="<?php echo SITEURL;?>images/profile_pic.jpg";
			}
			else
			{
				image="<?php echo SITEURL;?>uploads/"+image;
			}

			var tooltipOptions = {
			  marker: marker,
			  content: "<div><div><img src='"+image+"' alt='"+name+"' title='"+name+"' width='50px;' height='50px;'></div><span>"+name+"</span> ( "+distance+" Meters) <br / >"+address+"</div>",
			  cssClass: 'tooltip' // name of a css class to apply to tooltip
			};
			//var tooltip = new Tooltip(tooltipOptions);
		
			var infowindow = new google.maps.InfoWindow({
			  content: "<div><div style='float:left; width:27%;'><img src='"+image+"' alt='"+name+"' title='"+name+"' width='50px;' height='50px;'></div><div style='float:right; width:72%'>"+name+" ( "+distance+" Meters) <br / >"+address+"</div></div>",
			  maxWidth: 250
			});
			//open infowindo on click event on marker.
			google.maps.event.addListener(marker, 'click', function () {
			  if (lastOpenInfoWin) lastOpenInfoWin.close();
			  lastOpenInfoWin = infowindow;
			  infowindow.open(marker.get('map'), marker);
			});
		}
		
		
		
		
		
		  /* var latlong=lat+','+lng;
		geocoder.geocode( { 'address': latlong}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				results[0].geometry.location
				 }
			else
			{
				alert("Geocode was not successful for the following reason: " + status);
			}
		});*/		
	}
	
	mapmarkers(lat, lng, "<?php echo SITEURL;?>images/map_center.png", '', '', '', '', '', 'center');
	
	gettutorList();
	
	function gettutorList()
	{
		var lat=$('#UseraddressLattitude').val();
		var lng=$('#UseraddressLongitude').val();
		$.ajax({
			type: 'post',
			url: '<?php echo SITEURL;?>searches/getajaxtutorslist',
			data: {lat:lat, lng:lng},
			//dataType: "json",
			success: function(data) {
			  //  $('#data').html(data); 
				data=JSON.parse(data);
				tot=data.records;
	
				for(i=0; i<tot.length; i++)
				{
					address=tot[i].Useraddress.address;
					latitude=tot[i].Useraddress.lattitude;
					longitude=tot[i].Useraddress.longitude;
					distance=tot[i].Useraddress.distance;
					name=tot[i].User.name;
					image=tot[i].User.image;
					tutor=tot[i].Tutor;
					if (tutor.length>0)
					{
						for(j=0; j<tutor.length; j++)
						{
							subject=tutor[j].Subject.name;
							qual=tutor[j].Qualification.name;
							level=tutor[j].Level.name; 
						 //   alert(subject);
							//
							mapmarkers(latitude, longitude, "<?php echo SITEURL;?>images/map_matches.png", name, qual, distance, address, image, 'list');
							
						}
					}
				}
	
			}
		});
	}

    $('#radius').on('blur', function()
    {
        if($("#remradius").is(':checked'))
        {
            zoomval=setzoom();
            initialize(zoomval);
            subjectRange.setRadius(parseInt(0));
        }
        else
        {
            zoomval=setzoom();
            initialize(zoomval);
            subjectRange.setRadius(parseInt($(this).val()));
        }
    });

    $('#remradius').click(function()
    {
        if($(this).is(':checked'))
        {
            subjectRange.setRadius(parseInt(0));
        }
        else
        {
            zoomval=setzoom();
            initialize(zoomval);
            subjectRange.setRadius(parseInt($("#radius").val()));
        }
    });

}

YUI().use('autocomplete', function (Y) {
    var acNode = Y.one("#UseraddressAddress");

    acNode.plug(Y.Plugin.AutoComplete, {
        // Highlight the first result of the list.
        activateFirstItem: true,

        // The list of the results contains up to 10 results.
        maxResults: 10,

        // To display the suggestions, the minimum of typed chars is five.
        minQueryLength: 5,

        // Number of milliseconds to wait after user input before triggering a
        // `query` event. This is useful to throttle queries to a remote data
        // source.
        queryDelay: 50,

        // Handling the list of results is mandatory, because the service can be
        // unavailable, can return an error, one result, or an array of results.
        // However `resultListLocator` needs to always return an array.
        resultListLocator: function (response)
		{
            // Makes sure an array is returned even on an error.
            if (response.error) {
                return [];
            }

            var query = response.query.results.json,
                addresses;

            if (query.status !== 'OK') {
                return [];
            }

            // Grab the actual addresses from the YQL query.
            addresses = query.results;

            // Makes sure an array is always returned.
            return addresses.length > 0 ? addresses : [addresses];
        },

        // When an item is selected, the value of the field indicated in the
        // `resultTextLocator` is displayed in the input field.
        resultTextLocator: 'formatted_address',

        // {query} placeholder is encoded, but to handle the spaces correctly,
        // the query is has to be encoded again:
        //
        // "my address" -> "my%2520address" // OK => {request}
        // "my address" -> "my%20address"   // OK => {query}
        requestTemplate: function (query) {
            return encodeURI(query);
        },

        // {request} placeholder, instead of the {query} one, this will insert
        // the `requestTemplate` value instead of the raw `query` value for
        // cases where you actually want a double-encoded (or customized) query.
        source: 'SELECT * FROM json WHERE ' +
                    'url="http://maps.googleapis.com/maps/api/geocode/json?' +
                        'sensor=false&' +
                        'address={request}"',

        // Automatically adjust the width of the dropdown list.
        width: 'auto'
    });

    // Adjust the width of the input container.
    acNode.ac.after('resultsChange', function () {
        var newWidth = this.get('boundingBox').get('offsetWidth');
        //acNode.setStyle('width', Math.max(newWidth, 100));
    });

    // Fill the `lat` and `lng` fields when the user selects an item.
    acNode.ac.on('select', function (e) {
        var location = e.result.raw.geometry.location;
        for (var i=0; i<e.result.raw.address_components.length; i++)
        {
            for (var b=0;b<e.result.raw.address_components[i].types.length;b++)
            {
                if (e.result.raw.address_components[i].types[b] == "country")
                {
                //this is the object you are looking for
                country= e.result.raw.address_components[i];
                break;
                }
            }
        }
        $("#country").val(country.short_name);
        document.getElementById('UseraddressLattitude').value=location.lat;
        document.getElementById('UseraddressLongitude').value=location.lng;  
		zoomval=setzoom();
        initialize(zoomval);
    });
});


function setzoom()
{
	rad=$("#radius").val();
	if (rad>=0 && rad<=99)
	{
		return 17;
	}
	else if (rad>=100 && rad<=500)
	{
		return 15;
	}
	else if (rad>=501 && rad<=999)
	{
		return 14;
	}
	else if (rad>=10000 && rad<=99999)
	{
		return 16;
	}
	else if (rad>=100000 && rad<=999999)
	{
		return 16;
	}
	else
	{
		return 14;
	}
}

});
</script>
<?php //pr($data);?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<div>
    <div id="data"></div>
    <div style="padding-left: 44%">
        <table><tr><td>
<div class="yui3-skin-sam" >
<?php echo $this->Form->input('UseraddressAddress', array('label'=>false, 'class'=>'span3', 'style'=>'height:30px; width:350px;')); ?>
    <input type="text" name="UseraddressLattitude" id="UseraddressLattitude" value=""><input type="text" name="UseraddressLongitude" id="UseraddressLongitude" value="">
</div>
</td>
<td style="padding-left: 5px;"><?php echo $this->Form->input('country', array('options' =>$data,'label'=>false, 'class'=>'leftforminputtextd', 'empty'=>'Country', 'style'=>'height:30px; width:100px;')); ?>
</td>
<td style="padding-left: 5px;"> 
<?php echo $this->Form->input('radius', array('label'=>false, 'class'=>'span3', 'style'=>'height:30px; width:100px;', 'value'=>250)); ?>


<?php  $radius=array('0'=>'No Circle','100'=>'100', '200'=>'200', '300'=>'300', '500'=>'500', '800'=>'800', '1000'=>'1000', '1200'=>'1200', '1500'=>'1500', '2000'=>'2000', '2500'=>'2500', '3000'=>'3000', '4000'=>'4000', '5000'=>'5000');
      //echo $this->Form->input('radius', array('options' =>$radius, 'label'=>false, 'required'=>'required', 'class'=>'leftforminputtextd', 'style'=>'width:100px;', 'onchange'=>'drawcircle();'));?></td>
      <td>Remove circle <input type="checkbox" id="remradius" name="remradius"></td>
</tr></table></div>
<div id="map_canvas" style="width:100%;height:540px;"></div>
</div>