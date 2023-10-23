<html>
<head>
<script src="https://www.translucidus.weather.net/js/spin.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key="></script>
<style>
#loader1 {
    position:absolute;
    left:40%;
    top:35%;
    border-radius:20px;
    padding:25px;
    border:1px solid #777777;
    background:#ffffff;
    box-shadow:0px 0px 10px #777777;
}
</style>
</head>
<body>

<?php

	$DEBUG = false;
	
	if(isset($_GET["zipcode"]))
		$zipcode = $_GET["zipcode"];
	
	if(isset($_GET["lat"]))
		$lat = $_GET["lat"];
		
	if(isset($_GET["long"]))
		$long = $_GET["long"];
		
	
	if( empty($zipcode) && empty($lat) && empty($long) )
		$zipcode = "76092";

	function getLnt($zip){
		// var_dump($zip);
        $zip_array=array_map("str_getcsv",file("E:\data/freese/zip2stat_".$zip[0].".tm2"));		
        $rows=count($zip_array);
		// var_dump($zip_array[0][0]);
		// var_dump($zip_array[0][3]);
		// var_dump($zip_array[0][4]);
        if($zip<$zip_array[0][0]){
			$lat=$zip_array[0][3];
			$lng=(-1)*($zip_array[0][4]);
			$newzip=$zip_array[0][0];
        } else {
			for($row=0;$row<$rows;$row++){
				if($zip_array[$row][0]<=$zip){
					$lat=$zip_array[$row][3];
					$lng=(-1)*($zip_array[$row][4]);
					$newzip=$zip_array[$row][0];
				}
			}
        }
        unset($zip_array);
        return $lat."||".$lng."||".$newzip;
	}

	function getaddress($lat,$lng){
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&key=';
		//print $url;
		$json = @file_get_contents($url);
		$data=json_decode($json);
		$status = $data->status;
		if($status=="OK")
		{
		return $data->results[0]->formatted_address;
		}
		else
		{
		return false;
		}
	}

	if( isset($zipcode) || ( isset($lat) && isset($long) ) )
	{
		$url="";
		$time_stamp = date("Y-m-d");

		$time_stamp_begin = urlencode(date("Y-m-d"));
		$one_week_from_begin = strtotime(date("Y-m-d") . "+ 3 days");
		$time_stamp_end   = urlencode(date("Y-m-d",$one_week_from_begin));
		
		if( isset($lat) && isset($long) )
		{
			$url="https://graphical.weather.gov/xml/SOAP_server/ndfdXMLclient.php?whichClient=NDFDgen&lat={$lat}&lon={$long}&listLatLon=&lat1=&lon1=&lat2=&lon2=&resolutionSub=&listLat1=&listLon1=&listLat2=&listLon2=&resolutionList=&endPoint1Lat=&endPoint1Lon=&endPoint2Lat=&endPoint2Lon=&listEndPoint1Lat=&listEndPoint1Lon=&listEndPoint2Lat=&listEndPoint2Lon=&zipCodeList=&listZipCodeList=&centerPointLat=&centerPointLon=&distanceLat=&distanceLon=&resolutionSquare=&listCenterPointLat=&listCenterPointLon=&listDistanceLat=&listDistanceLon=&listResolutionSquare=&citiesLevel=&listCitiesLevel=&sector=&gmlListLatLon=&featureType=&requestedTime=&startTime=&endTime=&compType=&propertyName=&product=time-series&begin={$time_stamp_begin}&end={$time_stamp_end}&Unit=e&temp=temp&qpf=qpf&sky=sky&wx=wx&icons=icons&dew=dew&pop12=pop12&wdir=wdir&wspd=wspd&snow=snow&tmpabv14d=tmpabv14d&tmpblw14d=tmpblw14d&pcpabv14d=pcpabv14d&pcpblw14d=pcpblw14d&Submit=Submit";
		}
		else
		{
			$val = getLnt($zipcode);
			$valarray=explode("||",$val);			
			$lat=$valarray[0];
			$long=$valarray[1];
			$url="https://graphical.weather.gov/xml/SOAP_server/ndfdXMLclient.php?whichClient=NDFDgen&lat={$lat}&lon={$long}&listLatLon=&lat1=&lon1=&lat2=&lon2=&resolutionSub=&listLat1=&listLon1=&listLat2=&listLon2=&resolutionList=&endPoint1Lat=&endPoint1Lon=&endPoint2Lat=&endPoint2Lon=&listEndPoint1Lat=&listEndPoint1Lon=&listEndPoint2Lat=&listEndPoint2Lon=&zipCodeList=&listZipCodeList=&centerPointLat=&centerPointLon=&distanceLat=&distanceLon=&resolutionSquare=&listCenterPointLat=&listCenterPointLon=&listDistanceLat=&listDistanceLon=&listResolutionSquare=&citiesLevel=&listCitiesLevel=&sector=&gmlListLatLon=&featureType=&requestedTime=&startTime=&endTime=&compType=&propertyName=&product=time-series&begin={$time_stamp_begin}&end={$time_stamp_end}&Unit=e&temp=temp&qpf=qpf&sky=sky&wx=wx&icons=icons&dew=dew&wdir=wdir&wspd=wspd&snow=snow&tmpabv14d=tmpabv14d&tmpblw14d=tmpblw14d&pcpabv14d=pcpabv14d&pcpblw14d=pcpblw14d&Submit=Submit";
				
			
			
			$url="https://graphical.weather.gov/xml/SOAP_server/ndfdXMLclient.php?whichClient=NDFDgen&lat={$lat}&lon={$long}&listLatLon=&lat1=&lon1=&lat2=&lon2=&resolutionSub=&listLat1=&listLon1=&listLat2=&listLon2=&resolutionList=&endPoint1Lat=&endPoint1Lon=&endPoint2Lat=&endPoint2Lon=&listEndPoint1Lat=&listEndPoint1Lon=&listEndPoint2Lat=&listEndPoint2Lon=&zipCodeList=&listZipCodeList=&centerPointLat=&centerPointLon=&distanceLat=&distanceLon=&resolutionSquare=&listCenterPointLat=&listCenterPointLon=&listDistanceLat=&listDistanceLon=&listResolutionSquare=&citiesLevel=&listCitiesLevel=&sector=&gmlListLatLon=&featureType=&requestedTime=&startTime=&endTime=&compType=&propertyName=&product=time-series&begin={$time_stamp_begin}&end={$time_stamp_end}&Unit=e&temp=temp&qpf=qpf&sky=sky&wx=wx&icons=icons&dew=dew&wdir=wdir&wspd=wspd&snow=snow&tmpabv14d=tmpabv14d&tmpblw14d=tmpblw14d&pcpabv14d=pcpabv14d&pcpblw14d=pcpblw14d&Submit=Submit";
			if($DEBUG) {
				print $url . "\n";
				exit;
			}
		}
		$url1="https://graphical.weather.gov/xml/SOAP_server/ndfdXMLclient.php?whichClient=NDFDgen&lat={$lat}&lon={$long}&listLatLon=&lat1=&lon1=&lat2=&lon2=&resolutionSub=&listLat1=&listLon1=&listLat2=&listLon2=&resolutionList=&endPoint1Lat=&endPoint1Lon=&endPoint2Lat=&endPoint2Lon=&listEndPoint1Lat=&listEndPoint1Lon=&listEndPoint2Lat=&listEndPoint2Lon=&zipCodeList=&listZipCodeList=&centerPointLat=&centerPointLon=&distanceLat=&distanceLon=&resolutionSquare=&listCenterPointLat=&listCenterPointLon=&listDistanceLat=&listDistanceLon=&listResolutionSquare=&citiesLevel=&listCitiesLevel=&sector=&gmlListLatLon=&featureType=&requestedTime=&startTime=&endTime=&compType=&propertyName=&product=time-series&begin={$time_stamp_begin}&end={$time_stamp_end}&Unit=e&temp=temp&qpf=qpf&maxt=maxt&mint=mint&appt=appt&wgust=wgust&Submit=Submit";

		$address=getaddress($lat,$long);
		//print "add=".$address;
		// create curl resource
		$ch = curl_init();
		
		// set url
		//print $url;
		
		curl_setopt($ch, CURLOPT_URL, $url);		
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

		// $output contains the output string
		$output = curl_exec($ch);
		// var_dump($output);

		curl_setopt($ch, CURLOPT_URL, $url1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		
		$output1 = curl_exec($ch);
		// var_dump($output1);
		// close curl resource to free up system resources
		curl_close($ch); 

	}
	else
	{
		print "Please enter zipcode";
		exit;
	}
?>
<style>
.whiteHat {
  border: none;
  position: absolute;
  height:50px;
  width:50px;
  color:black;
  margin-left: -15px!important;
}
.whiteHat1 {
  border: none;
  position: absolute;
  height:25px;
  width:25px;
  color:black;
  font-size: 10px;
}

</style>

<script>
    google.charts.load('current', {packages: ['corechart', 'line']});

    function drawChart() {

		var data = new google.visualization.DataTable();		
		data.addColumn('string', 'Date');
		data.addColumn({type: 'string', role: 'annotation'});
		data.addColumn('number', 'Temperature (°F)');	
		data.addColumn('number', 'Dew Point (°F)');	
		data.addColumn('number', 'Snow (Inch)');
		data.addColumn('number', 'Rain (1/100 Inch)');
		
		var olddate="";
		if(temperaturedata[0]=="")
		{
			alert("Data Not available for this Location");
			return;
		}
		for (index = 0; index < temperaturedata.length; index++) {		
			var datetimesplit=completedate[index].split("T");		
			var datesplit=datetimesplit[0].split("-");
			var hour=datetimesplit[1].substring(0, 2);
			
			if(olddate!=datetimesplit[0])
			{
				//alert(hour+"="+datetimesplit[0]+"="+parseInt(temperaturedata[index]));
				data.addRow([hour,datetimesplit[0],parseInt(temperaturedata[index]),parseInt(temperaturedata1[index]), parseInt(precipitationsnowdata[index]), parseInt(precipitationdata[index])]);
				// data.addRow([hour,datetimesplit[0],parseInt(temperaturedata1[index])]);
			}
			else
			{
				//alert(hour+"="+parseInt(temperaturedata[index]));
				data.addRow([hour,'',parseInt(temperaturedata[index]),parseInt(temperaturedata1[index]), parseInt(precipitationsnowdata[index]), parseInt(precipitationdata[index])]);
				// data.addRow([hour,'',parseInt(temperaturedata1[index])]);
			}
			
			olddate=datetimesplit[0];

		}
		
		var title="<?php echo $address ?>";
		var linearOptions = {
			title: 'Meteogram - '+title,
			legend:'top',
			focusTarget: 'category',
			crosshair: { trigger: 'both', color: 'red'},
			chartArea: {'width': '80%', 'height': '80%'},
			width: 1700,
			height: 700,		
			margin: 150,	
			hAxis: {
				title: 'Hours'
			},
			vAxis: {
				title: '',
				ticks: [-80,0,10,20,30,40,50,60, 70,80, 90, 100]
			},
			axes: {
				x: {
					0: { side: 'top'}
				}
			},
			vAxes:{
				0: {title: ''}				
			},
			pointSize: 10,
			series: {
				0: { pointShape: 'circle' },
			}
		};

		var logOptions = {
			title: 'World Population Since 1400 A.D. in Log Scale',
			legend: 'none',
			width: 250,
			height: 500,
			hAxis: {
			title: 'Date',
			ticks: [0, 25, 50, 75, 100]
			},
			vAxis: {
			title: 'Population (millions)',
			scaleType: 'log',
			ticks: [0, 1000, 2000, 4000, 6000]
			}
		};

		var container = document.getElementById('linear_div');

		var l = 0;			
		var i=0;
		var linearChart = new google.visualization.LineChart(container);
	  	google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('img'));
					whiteHat.src = images[i];
					whiteHat.className = 'whiteHat';

					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos-220) + 'px';
					whiteHat.style.left = (xPos) + 'px';
					i++;
				}
			});
			
		});		
		
		var j=1;
		google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					var direction = directiondata[j];
					
					if((direction>=0 && direction<=10) || (direction>=350 && direction<360)){
						whiteHat.innerHTML = 'N</br><p>&#8595</p>';
					} else if(direction>=20 && direction<=30) {
						whiteHat.innerHTML = 'NNE</br><p style="transform:rotate(22.5deg)">&#8595</p>';
					} else if(direction>=40 && direction<=50) {
						whiteHat.innerHTML = 'NE</br><p>&#8601</p>';
					} else if(direction>=60 && direction<=70) {
						whiteHat.innerHTML = 'ENE</br><p style="transform:rotate(22.5deg)">&#8601</p>';
					} else if(direction>=80 && direction<=100) {
						whiteHat.innerHTML = 'E</br><p>&#8592</p>';
					} else if(direction>=110 && direction<=120) {
						whiteHat.innerHTML = 'ESE</br><p style="transform:rotate(22.5deg)">&#8592</p>';
					} else if(direction>=130 && direction<=140) {
						whiteHat.innerHTML = 'SE</br><p>&#8598</p>';
					} else if(direction>=150 && direction<=160) {
						whiteHat.innerHTML = 'SSE</br><p style="transform:rotate(22.5deg)">&#8598</p>';
					} else if(direction>=170 && direction<=190) {
						whiteHat.innerHTML = 'S</br><p>&#8593</p>';
					} else if(direction>=200 && direction<=210) {
						whiteHat.innerHTML = 'SSW</br><p style="transform:rotate(22.5deg)">&#8593</p>';
					} else if(direction>=220 && direction<=230) {
						whiteHat.innerHTML = 'SW</br><p>&#8599</p>';
					} else if(direction>=240 && direction<=250) {
						whiteHat.innerHTML = 'WSW</br><p style="transform:rotate(22.5deg)">&#8599</p>';
					} else if(direction>=260 && direction<=280) {
						whiteHat.innerHTML = 'W</br><p>&#8594</p>';
					} else if(direction>=290 && direction<=300) {
						whiteHat.innerHTML = 'WNW</br><p style="transform:rotate(22.5deg)">&#8594</p>';
					} else if(direction>=310 && direction<=320) {
						whiteHat.innerHTML = 'NW</br><p>&#8600</p>';
					} else if(direction>=330 && direction<=340) {
						whiteHat.innerHTML = 'NNW</br><p style="transform:rotate(22.5deg)">&#8600</p>';
					}
					
					whiteHat.className = 'whiteHat1';
					
					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos + 80) + 'px';
					whiteHat.style.left = (xPos) + 'px';
					
					j++;
				}
			});
			
		});

		google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					if(l == 1){
						whiteHat.innerHTML = 'Rain (inch)';
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos - 15) + 'px';
						whiteHat.style.width = '100px';
						whiteHat.style.fontsize = '14px';
					}else if(l == 2){
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 25) + 'px';
						whiteHat.style.width = '50px';
						whiteHat.style.fontsize = '14px';
						whiteHat.style.background = 'rgb(16, 150, 24)';
					}else if(l == 3){
						whiteHat.innerHTML = 'Snow (inch)';
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 15) + 'px';
						whiteHat.style.width = '100px';
						whiteHat.style.fontsize = '14px';
					}else if(l == 4){
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 55) + 'px';
						whiteHat.style.width = '50px';
						whiteHat.style.fontsize = '14px';
						whiteHat.style.background = 'rgb(255, 153, 0)';
					}else if(l == 5){
						whiteHat.innerHTML = 'Cloud (%)';
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos +45) + 'px';
						whiteHat.style.width = '100px';
						whiteHat.style.fontsize = '14px';
					}else if(l == 6){
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 85) + 'px';
						whiteHat.style.width = '50px';
						whiteHat.style.fontsize = '14px';
						whiteHat.style.background = 'rgb(0, 153, 198)';
					}else if(l == 7){
						whiteHat.innerHTML = 'Gust (mph)';
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 75) + 'px';
						whiteHat.style.width = '100px';
						whiteHat.style.fontsize = '14px';
					}else if(l == 8){
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 115) + 'px';
						whiteHat.style.width = '50px';
						whiteHat.style.fontsize = '14px';
						whiteHat.style.background = 'rgb(153, 0, 153)';
					}else if(l ==9){
						whiteHat.innerHTML = 'Wind (mph)';
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 105) + 'px';
						whiteHat.style.width = '100px';
						whiteHat.style.fontsize = '14px';
					}else if(l == 10){
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos + 150) + 'px';
						whiteHat.style.left = (xPos + 145) + 'px';
						whiteHat.style.width = '50px';
						whiteHat.style.fontsize = '14px';
						whiteHat.style.background = 'rgb(255, 128, 223)';
					}else if(l == 0){
						whiteHat.className = 'whiteHat1';
						whiteHat.style.top = (yPos ) + 'px';
						whiteHat.style.left = (xPos-80) + 'px';
						whiteHat.style.width = '42px';
						whiteHat.style.fontsize = '14px';
						whiteHat.style.background = 'white';
					}
					
					l++;
				}
			});
			
		});

		var r=0;		
	  	google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					if(precipitationdata[r] != null){
						whiteHat.innerHTML = precipitationdata[r];
					}else{
						whiteHat.innerHTML = ''
					}
					
					whiteHat.className = 'whiteHat1';

					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos-170) + 'px';
					whiteHat.style.left = (xPos-20) + 'px';
					whiteHat.style.background = 'rgb(16, 150, 24)';					
					whiteHat.style.width = '60px';		
					whiteHat.style.fontsize = '16px';			
					
					r++;
				}
			});
			
		});

		var s=0;		
	  	google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					if(precipitationsnowdata[s] != null){
						whiteHat.innerHTML = precipitationsnowdata[s];
					}else{
						whiteHat.innerHTML = ''
					}
					
					whiteHat.className = 'whiteHat1';

					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos-140) + 'px';
					whiteHat.style.left = (xPos-20) + 'px';
					whiteHat.style.background = 'rgb(255, 153, 0)';					
					whiteHat.style.width = '60px';		
					whiteHat.style.fontsize = '16px';			
					
					s++;
				}
			});
			
		});

		var k=0;		
	  	google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					if(cloudamountdata[k] != null){
						whiteHat.innerHTML = cloudamountdata[k];
					}else{
						whiteHat.innerHTML = ''
					}
					
					whiteHat.className = 'whiteHat1';

					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos-110) + 'px';
					whiteHat.style.left = (xPos-20) + 'px';
					whiteHat.style.background = 'rgb(0, 153, 198)';					
					whiteHat.style.width = '60px';		
					whiteHat.style.fontsize = '16px';			
					
					k++;
				}
			});
			
		});

		var g = 0;
		google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					if(windgustdata[g] != null){
						whiteHat.innerHTML = windgustdata[g];
					}else{
						whiteHat.innerHTML = ''
					}
					
					whiteHat.className = 'whiteHat1';

					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos-80) + 'px';
					whiteHat.style.left = (xPos-20) + 'px';
					whiteHat.style.background = 'rgb(153, 0, 153)';
					whiteHat.style.width = '60px';		
					whiteHat.style.fontsize = '16px';			
					
					g++;
				}
			});
			
		});

		var w = 0;
		google.visualization.events.addListener(linearChart, 'ready', function () {
    		Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect) {
		
				if ((rect.getAttribute('width') === '1') && (rect.getAttribute('fill') === '#999999')) {
					
					var xPos = parseFloat(rect.getAttribute('x'));
					var yPos = parseFloat(rect.getAttribute('y'));

					var whiteHat = container.appendChild(document.createElement('div'));
					if(windspeeddata[w] != null){
						whiteHat.innerHTML = windspeeddata[w];
					}else{
						whiteHat.innerHTML = ''
					}
					
					whiteHat.className = 'whiteHat1';

					// 16x16 (image size in this example)
					whiteHat.style.top = (yPos-50) + 'px';
					whiteHat.style.left = (xPos-20) + 'px';
					whiteHat.style.background = 'rgb(255, 128, 223)';
					whiteHat.style.width = '60px';		
					whiteHat.style.fontsize = '16px';			
					
					w++;
				}
			});
			
		});

		
 
		linearChart.draw(data, google.charts.Line.convertOptions(linearOptions));		
		
		
	}
	
	var data = [];
	var temperaturedata=[];
	var temperaturedata1=[];
	var aptemperaturedata = [];
	var precipitationdata=[];
	var directiondata=[];
	var windspeeddata=[];
	var completedate=[];
	var cloudamountdata=[];
	var precipitationsnowdata =[];
	var label=[];
	var images=[];
	var images1=[];
	var data=<?php echo json_encode($output) ?>;		
	console.log(data);
	var precipdate=[];
			
	$(data).find('temperature').first().find('value').each(function(){		
		temperaturedata.push($(this).text().toString());		
	});	

	$(data).find('temperature').eq(1).find('value').each(function(){		
		temperaturedata1.push($(this).text().toString());		
	});	
	
	var cc = 0;
	$(data).find('precipitation').eq(0).find('value').each(function(){
		var amountliquid = $(this).text().toString();
		precipitationdata.push(amountliquid);
		cc++;
		if(cc<$(data).find('precipitation').first().find('value').length){
			precipitationdata.push(amountliquid);
		}
		
	});
	
	var cc = 0;
	$(data).find('precipitation').eq(1).find('value').each(function(){
		var amountsnow = $(this).text().toString();
		precipitationsnowdata.push(amountsnow);
		if(cc<$(data).find('precipitation').first().find('value').length){
			precipitationsnowdata.push(amountsnow);
		}
	});	

	$(data).find('cloud-amount').eq(0).find('value').each(function(){
		cloudamountdata.push($(this).text().toString());
	});

	$(data).find('direction').find('value').each(function(){
		directiondata.push($(this).text().toString());
	});
	
	$(data).find('cloud-amount').find('value').each(function(){
		cloudamountdata.push($(this).text().toString());
	});
	
	$(data).find('time-layout').first().find('start-valid-time').each(function(){		
		var timesplit=$(this).text().toString().split("T");		
		completedate.push($(this).text().toString());
	});

	$(data).find('time-layout').eq(1).find('start-valid-time').each(function(){		
		precipdate.push($(this).text().toString());		
	});	

	$(data).find('wind-speed').find('value').each(function(){
		windspeeddata.push($(this).text().toString());		
	});
	
	$(data).find('conditions-icon').first().find('icon-link').each(function(){		
		images.push($(this).text().toString());		
	});
	
	google.charts.setOnLoadCallback(drawChart);

	var data1=<?php echo json_encode($output1) ?>;		

	var maximumdata = [];
	var maximumdate = [];
	var minimumdata = [];
	var minimumdate = [];
	var windgustdata = [];
	var windgustdate = [];

	$(data1).find('temperature').eq(2).find('value').each(function(){		
		aptemperaturedata.push($(this).text().toString());		
	});	

	var att1 = $(data1).find('temperature').eq(2).attr('time-layout');

	$(data1).find('temperature').eq(0).find('value').each(function(){
		maximumdata.push($(this).text().toString());
	});

	$(data1).find('time-layout').eq(0).find('start-valid-time').each(function(){
		maximumdate.push($(this).text().toString());
	});
	
	$(data1).find('temperature').eq(1).find('value').each(function(){
		minimumdata.push($(this).text().toString());
	});
	$(data1).find('time-layout').eq(1).find('start-valid-time').each(function(){
		minimumdate.push($(this).text().toString());
	});
	var cc = 0;
	$(data1).find('wind-speed').eq(0).find('value').each(function(){		
		windgustdata.push($(this).text().toString());
	});
	$(data1).find('time-layout').eq(4).find('start-valid-time').each(function(){		
		windgustdate.push($(this).text().toString());
	});
	
</script>

<table class="columns">
	<tr>
		<td><div id="linear_div"></div></td>
	</tr>
</table>

<!-- <script>
	$(document).ready(function () {
	window.parent.document.getElementById("loader1").style.display="none";
	//$( "loader1" ).parents().css( "background-color", "red" );
	//$(this).closest('.loader').hide()
	});
</script> -->
</body>
</html>




