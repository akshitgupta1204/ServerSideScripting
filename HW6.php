<?php


if(isset($_POST['idplace']))
    {
      
      $pID = $_POST['idplace'];
      $place_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$pID."&key=AIzaSyCuVEKl8_oFsdq7b7_WGG4qi0dCbQ9K6dE";

      $placeContents = file_get_contents($place_url); 
      
      $placeConArray = json_decode($placeContents,true);
      
      $no_photos = 5;
      
      if(empty($placeConArray['result']['photos']))
        {
              $no_photos = 0;
        } 
        else
        {
             if(sizeof($placeConArray['result']['photos']) < $no_photos)
             { 

                $no_photos = sizeof($placeConArray['result']['photos']);
                    
             }
         }
       
      $high_image_url = array();
      for($q=0; $q<$no_photos; $q++)
      { 

         
          array_push($high_image_url,'https://maps.googleapis.com/maps/api/place/photo?maxwidth=750&photoreference='.urlencode($placeConArray['result']['photos'][$q]['photo_reference']).'&key=AIzaSyCuVEKl8_oFsdq7b7_WGG4qi0dCbQ9K6dE');  

       }
    
       for($y=0; $y<sizeof($high_image_url); $y++)

       { 
             $url=$high_image_url[$y];
             $contents=file_get_contents($url);
             $save_path= $y.".png";
             file_put_contents($save_path,$contents);
       }   
       echo $placeContents;
       exit();
        
  }
  $currentLocLat = "0.0";
  $currentLocLong = "0.0";
  $near_json = "";
 if(isset($_POST["S"]))
 {
 

       $kword = $_POST["keyword"];
       $cat   = $_POST["items"];
       
       if(!isset($_POST["Distance"]) || $_POST["Distance"] == 0) 
       {
         $dist = 10*1609;
       }

       else
       {
        $dist = $_POST["Distance"]*1609; 
       }
       
       
      
        if(!empty($_POST["location"]))
        {
         $loc  = $_POST["location"]; 
         $geo_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($loc).'&key=AIzaSyCuVEKl8_oFsdq7b7_WGG4qi0dCbQ9K6dE';
         
        
         $geo_json = file_get_contents($geo_url);
         $geo_array = json_decode($geo_json, true); 
          
         //store latitude and longitude of the given location. 
         $lat = $geo_array['results'][0]['geometry']['location']['lat'];
         $lng = $geo_array['results'][0]['geometry']['location']['lng'];  
         
         //create url to get nearby places.
         $near_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.urlencode($lat).','.urlencode($lng).'&radius='.urlencode($dist).'&type='.urlencode($cat).'&keyword='.urlencode($kword).'&key=AIzaSyCuVEKl8_oFsdq7b7_WGG4qi0dCbQ9K6dE';
         

         
         $near_json = file_get_contents($near_url);
         
         $near_array = json_decode($near_json, true); 




        }
         

       else
       {
        

        $lat = $_POST['originlat'];
        $lng = $_POST['originlon'];

        
        $near_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.urlencode($lat).','.urlencode($lng).'&radius='.urlencode($dist).'&type='.urlencode($cat).'&keyword='.urlencode($kword).'&key=AIzaSyCuVEKl8_oFsdq7b7_WGG4qi0dCbQ9K6dE'; 
        
         
         $near_json = file_get_contents($near_url);
         

         $near_array = json_decode($near_json, true); 


       }
       $currentLocLat = $lat;
       $currentLocLong = $lng;
       


   }

?>

<html>
<head>

<TITLE>Homework 6</TITLE>

<style>
.SearchBox{
   height: 230px;
   width: 770px;
   border-style:solid;
   border-color: #A8A8A8;
   margin-left: 390px;
   margin-top: 20px;
}
.SearchBox h1{
  text-align: center;
}
.line{
  margin-top: -14px;
  margin-right: 10px;
  margin-left: 10px;
  border-color: #A8A8A8;

}
.title{
  margin-top: -15px;
}

.location{
  margin-left: 315px;
}

.Search{
  margin-top: -20px;
  margin-left: 70px;
  border-radius: 8px;
  
  width: 58px;


}


#submit1{
  border-radius: 4px;
  background-color: white;
  border-style: solid;
  border-width: 1px;
}

.Clear{

  margin-top: 18px;
  margin-left: 140px;
  border-radius: 2px;
  
  width: 47px;
}

#clearmyform{

  border-radius: 4px;
  background-color: white;
  border-style: solid;
  border-width: 1px;
}

.place
{
  text-decoration: none;
  color: black;
}







#ptable{

  border-collapse: collapse;
  margin-top: 25px;
  padding-right:30px;
  margin-left:100px;
  margin-right:0px; 
  padding-left:30px;
  width: 1300px;
  

}





.placename{
    
    text-align: center;
}

#pheading1,#pheading2{
  text-align: center;
}


#photos {
  
  margin-left: 380px;
  

}

#photos td{
   border: 12px solid white;
   outline: 2px solid grey;
}





#reviews{
  margin-left:390px;
  border-collapse: collapse;
}

#reviews th{
  border-style: none;
}

#reviews tr{
  border-collapse: collapse;
}

#identifier1,#identifier2
{
  margin-top: 15px;
}


#pointer1 {
      height: 9px;
      width: 31px;
      background-image: url("http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png");
      background-size: 31px 22px;
      margin-left: 745px;
      padding-bottom: 10px;
      background-repeat: no-repeat;
    }

    #pointer2{

      height: 9px;
      width: 31px;
      background-image: url("http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png");
      margin-left: 745px;
      background-size: 31px 22px;
      padding-bottom: 10px;
      background-repeat: no-repeat;

    }


 #map {
        height: 315px;
        width: 355px;
       }

 .mainmap {
      position: relative;
      user-select: none;
  }
  

 
  .mainmap .map1 {
      visibility: hidden;
      color: #fff;
      text-align: center;
      border-radius: 7px;
      padding: 6px 0;
      position: absolute;
      z-index: 1;
      left: 5%;
      margin-left: auto;
      margin-right: auto;
  }



.mainmap .showmap {
    visibility: visible;
}
   #ModeSelector {
        position: absolute;
        z-index: 6;
        color: black;
        cursor: pointer;
        text-align: center;
        font-family: 'calibri','sans-serif';
      }

      #walking:hover{
        background: white;
      }

      
      #ERROR {
        width: 100%;
        height:10%;
        text-align: center;
      }
      .clickAddress,.clickPlaces {
        cursor: pointer;
      }
      
      .clickAddress:active{
         color: grey;
      } 


.mainmap .showmap {
    visibility: visible;
} 

    


</style>

<script type="text/javascript">

outputHtml=''; 
output1='';
output2='';
name_place = '';
map = undefined;
origin = undefined;
destination = undefined;



placesnearby = <?php 
if($near_json==NULL)
 echo "''";
else
  echo $near_json; ?>;

//Call the initial place address table
if(placesnearby != '')
  {
    PlaceAddressTable(placesnearby);
  }

  
  
  //Reload data
  function regenerateData() {
    if(outputHtml != undefined)
    {
      document.getElementById("response").innerHTML = outputHtml; 
    }
}



  //Enable here 
  function Herefunc()
  {
    document.getElementById("location").disabled = true;
  }



  //Enable location
  function Location()
  {
    document.getElementById("location").disabled = false;
  }




  //Enable disable search button
  function Startpage() {
 
  if (window.XMLHttpRequest) {
        var xmlhttp = new XMLHttpRequest();
  } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  var location = "http://ip-api.com/json";
  xmlhttp.open("GET",location,false); 

  
  
  xmlhttp.onreadystatechange = function () {
  if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            
   }
   
};

  try{
  xmlhttp.send(null); 
  }

  catch(e){
    
    document.getElementById("submit1").disabled = true;
    
    return;
  }
 
 jsonDoc=xmlhttp.responseText;
 
  if(xmlhttp.status == 404)
  {
    document.getElementById("submit1").disabled = true; 
    jsonDoc = null;
  }

 try{  
     jsObject = JSON.parse(jsonDoc);
     document.getElementById("originLat").value = jsObject['lat'];
     document.getElementById("originLon").value = jsObject['lon'];
    }

   catch(e){ 
   
    document.getElementById("submit1").disabled = true;
    return;  
  }


}





  //toggle photos
  function switchPhotos()
        {
          visibileV = document.getElementById("dataForPhotos").style.visibility;
          if(visibileV == "visible")
          {
             document.getElementById("dataForPhotos").style.visibility = "collapse"
            document.getElementById("pointer2").style.backgroundImage = "url(http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png)";
            document.getElementById("pprompt").innerHTML = "Click to show photos";



          }
          else
          {
             document.getElementById("dataForPhotos").style.visibility = "visible";
            document.getElementById("pointer2").style.backgroundImage = "url(http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png)";
            document.getElementById("pprompt").innerHTML = "Click to hide photos";
            document.getElementById("dataForReview").style.visibility = "collapse"
            document.getElementById("pointer1").style.backgroundImage = "url(http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png)";
            document.getElementById("rprompt").innerHTML = "Click to show reviews";

          }
          
        }

  
  
  
  //toggle reviews
  function switchReview()
        {
          visibileV = document.getElementById("dataForReview").style.visibility;
          if(visibileV == "visible")
          {
             document.getElementById("dataForReview").style.visibility = "collapse"
            document.getElementById("pointer1").style.backgroundImage = "url(http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png)";
            document.getElementById("rprompt").innerHTML = "Click to show reviews"; 
            


          }
          else
          {
             document.getElementById("dataForReview").style.visibility = "visible";
            document.getElementById("pointer1").style.backgroundImage = "url(http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png)";
            document.getElementById("rprompt").innerHTML = "Click to hide reviews";
            document.getElementById("dataForPhotos").style.visibility = "collapse"
            document.getElementById("pointer2").style.backgroundImage = "url(http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png)";
            document.getElementById("pprompt").innerHTML = "Click to show photos";

          }
          
        }
   



  //generate photos
  function Photos(storephotos)
   {
     ncols = storephotos.result.photos;
         var plength = 5; 
          if(ncols=="" || ncols == undefined)
          {
            output2+='<table id="photos" border="1px" width = "770px" style="border-collapse:collapse;">';
            output2+='<th>No Photos has been found</th>';
            output2+='</table>'; 
                plength= 0;
          } 
          else
          {
              if(ncols.length < plength)
              { 


                  plength = ncols.length;  
              }
               output2 = '<table id ="photos" border="1px">';    
            output2 += '<tbody>'; 
            for(j=0; j<plength; j++)
            {
              output2 += '<tr>';
       
           
              output2 += "<td><a href=" + j + ".png" + " target='_blank'><img src = "+j+".png" +"></a></td>";
              output2 += '</tr>';
            }
            output2 += '</tbody>';
            output2 += '</table>';
          }
     
   }





  //generate reviews
   function Reviews(storephotos)
    {
        name_place = storephotos.result.name;
        
        nrows = storephotos.result.reviews;
            var rlength = 5; 
            if(nrows=="" || nrows == undefined)
            {
                  rlength = 0;
                  output1+='<table id="reviews" border="1px" width = "770px" style="border-collapse:collapse;border-color:#d8d8d8; margin-top:10px">';
              output1+='<th>No Records has been found</th>';
              output1+='</table>'; 
            } 
            else
            {
                if(nrows.length < rlength)
                { 

                    rlength = nrows.length;
                }
            
          output1  = '<table id ="reviews" border="2px" width = "770px"style="border-collapse:collapse;border-color:#d8d8d8; margin-top:10px">';    
          output1 += '<tbody>';
          for(i=0; i<rlength; i++)
          {    
        
            output1 += '<tr border="0px">';
            output1 += '<td style="border:0px;" align="right" width="50%"><img src ="'+nrows[i]["profile_photo_url"]+'" height="30px" width="30px"/></td>';
            output1 += '<td style="border:0px" style="text-align:left;" width="50%">' + nrows[i]["author_name"] + '</td></tr>'; 
            output1 += '<tr><td colspan="2">' + nrows[i]["text"]+ '</td>'; 
            output1 += '</tr>';
          }
          output1 += '</tbody>';
          output1 += '</table>'; 
        }
   }





  //Now generaing all the places and reviews
  function PlacesandReview(populatedata)
        {
          Reviews(populatedata);
          Photos(populatedata);
          dataForPlace = populatedata.result.name;
          populateHtml = '<h2 style="text-align:center;">'+dataForPlace+'</h2>';
          populateHtml += '<p id="rprompt" style="text-align:center;">Click to show reviews<p>';
          populateHtml +=  '<div id="pointer1" class="clickAddress" onclick="switchReview(this);"></div>';
          populateHtml+=   '<div id="dataForReview" style="visibility: collapse; width:760px;">';
          populateHtml+=    output1+'</div>';
          populateHtml+= '<p id="pprompt" style="text-align:center;">Click to show photos</p>';
          populateHtml+= '<div id="pointer2" class="clickAddress" onclick="switchPhotos(this);"></div>';
          populateHtml+= '<div id="dataForPhotos" style="visibility:collapse; width:760px;">';
          populateHtml+= output2+'</div>';
          document.getElementById('response').innerHTML = populateHtml;
          
        } 

  



  //Clicking place names and sending request through AJAX
  function clickPlace(e)
    {
        identifier2 = (e.id)
        placeID = identifier2.substring(0,identifier2.indexOf("place")); 
        if(window.XMLHttpRequest)
        {
          xmlhttp2 = new XMLHttpRequest();
        }
        else
        {
          xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url="HW6.php";
        var vars="idplace=" + placeID;
        xmlhttp2.open('POST',url,true);
        xmlhttp2.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp2.onreadystatechange=function(){

        if(xmlhttp2.readyState==4 && xmlhttp2.status==200){
           respData=xmlhttp2.responseText;
           populatedata = JSON.parse(respData);
           PlacesandReview(populatedata);
           
        }

      }
      xmlhttp2.send(vars);
        
    }


   //Displaying Map
   function fetchgoogleMap(ele,ob1,ob2)
    {
      identifier1 = (ele.id);
      addressId = identifier1.substring(0,identifier1.indexOf("address"));  
      aid = document.getElementById(addressId);
      if(aid.style.visibility == "hidden")
      {   
        placedClickedLat = ob1;
        placedClickedLong = ob2;
        destPlaceDiv = document.getElementById(addressId+'place');
        destination = destPlaceDiv.innerHTML;
        aid.style.visibility = "visible";
        mapid = document.getElementById('map');
        mapid.style.visibility = "visible";
        modeid = document.getElementById('ModeSelector')
        modeid.style.visibility = "visible";
        dLatitude = Number.parseFloat(ob1);
        dLongitude = Number.parseFloat(ob2)
        aid.appendChild(modeid);
        aid.appendChild(mapid);
      
      }
      else
      {
        mapid = document.getElementById('map');
        mapid.style.visibility = "hidden";
        modeid = document.getElementById('ModeSelector')
        modeid.style.visibility = "hidden";
        aid.style.visibility = "hidden";

      } 
        
    } 




  //Showing place and address table.
  function PlaceAddressTable(loc)
  {
    object = loc;

           if(object.results.length == 0)
           {
            outputHtml+='<table id="ptable"  border="1px" style="border-collapse:collapse;background-color:white;">';
        outputHtml+='<th>No Records has been found</th>';
        outputHtml+='</table>'; 
           } 
         
            
           else{ 
            root = object.documentElement;



            
            outputHtml  = '<table id = "ptable" border="1px">';
             
            outputHtml += '<tbody>';
            outputHtml += '<tr>';
            
            outputHtml += '<th>Category</th>'; 
            outputHtml += '<th>Name</th>';
            outputHtml += '<th>Address</th>';
            outputHtml += '</tr>';

            
            n_rows = object.results; 
            

            

            for (i = 0; i < n_rows.length; i++) {
                  var id; 
                  current = n_rows[i];
                  var keys = Object.keys(current);
                  outputHtml += '<tr>';
                  for(j=0; j<keys.length; j++)
                  {
                    if(keys[j] == "place_id")
                    {
                        id = current[keys[j]];
                    }
                    if(keys[j] == 'geometry')
                    {
                     latitude = (current[keys[j]]['location']['lat']);
                     longitude = (current[keys[j]]['location']['lng']);
                    }
                  
                  }
                  outputHtml += '<td><img src="' + n_rows[i]["icon"] +'"></td>';
                  outputHtml += '<td><span class="clickPlaces" name ="places" id ="'+id+"place"+'" onclick="clickPlace(this)">' +n_rows[i]["name"]+ '</span></td>'; 
                   

                  outputHtml+='<td class="mainmap" ><p class="clickAddress" id="'+id+"address"+'" onclick="fetchgoogleMap(this,'+latitude+','+longitude+');" style="margin:0px;">'+n_rows[i]["vicinity"]+'</p><span class="map1" id="'+id+'" style="visibility:hidden;"></span></td>';
                outputHtml+='<td id="'+id+'latip" style="display:none;">'+latitude+'</td>';
                outputHtml+='<td id="'+id+'longip" style="display:none;">'+longitude+'</td>';
                 
                  outputHtml += '</tr>';

                                 
            } 
             outputHtml += '</tbody>';
             outputHtml += '</table>';
           } 
            
         }






   


  </script>
  </head>


<BODY onload = "Startpage(); regenerateData();">

 <div class = "SearchBox">
 <div class = "title"><H1><I>Travel and Entertainment Search</I></H1></div>
 <div class = "line"><hr></div>
 <FORM id = "clear1" ACTION="<?php $_SERVER['PHP_SELF'] ?>" METHOD=POST>
 &nbsp;<b>Keyword</b> <input id = "keyword" type="text" name="keyword" required><br>
 &nbsp;<b>Category</b> <select name="items">
    <option id="default">default</option> 
    <option id="cafe">cafe</option>
    <option id="bakery">bakery</option>
    <option id="restaurant">restaurant</option>
    <option id="beauty salon">beauty salon</option>
    <option id="casino">casino</option>
    <option id="movie theater">movie theater</option>
    <option id="lodging">lodging</option>
    <option id="airport">airport</option>
    <option id="train station">train station</option>
    <option id="subway station">subway station</option>
    <option id="bus station">bus station</option>
 </select><br>
 &nbsp;<b>Distance(miles)</b> <input type="text" id = 'dist' name="Distance" placeholder=10>
 <b>from</b> <input type="radio" id = "here" value = "Yes" name="Here" onclick = "Herefunc()" checked style="color:blue;">Here<br>
 <div class = "location"><input type="radio" id = "locId" value = "No" name="Here" onclick = "Location()">&nbsp;&nbsp;<input type="text" id="location" name = "location" placeholder="location" required disabled="disabled">
 </div>
  
 
 <div class = "Clear"><input type="button" id="clearmyform" value="Clear" onClick="this.form.reset()" /></div>


 <div class = "Search"> <button id = "submit1" name = "S" type="submit" value="Search">Search</button></div>
  

  <input type="hidden" id="originLat" name="originlat"/>
  <input type="hidden" id="originLon" name="originlon"/>
 </form>
</div>

<div id='response'>
</div>

<div id="ModeSelector" style="visibility: hidden;display: block;">
      <div id="walking" style="background-color: #C8C8C8; width: 80px; text-align: left;">Walk there</div>
      <div id="biking" style="background-color: #C8C8C8; width: 80px; text-align: left;">Bike there</div>
      <div  id="driving" style="background-color: #C8C8C8; width: 80px; text-align: left;">Drive there</div>
      
    </div>
    <div id="map" style="visibility: hidden;"></div>
    &nbsp;
    <div id="ERROR" style="visibility: hidden;"></div>
    <script>
      function initMap() {
        var markers = [];
        

       
        var directionsService = new google.maps.DirectionsService;

       
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: {lat: 34.0218555, lng: -118.515775}
        });

     
        var DisplayDirections = new google.maps.DirectionsRenderer({map: map});

        var stepDisplay = new google.maps.InfoWindow;
          
        travelMode = '';
        var onChangeHandler = function(e) {
          identifier1 = e.target.id;
          if(identifier1 == 'walking')
            travelMode = "WALKING";
          if(identifier1 == 'biking')
            travelMode = "BICYCLING";
          if(identifier1 == 'driving')
            travelMode = "DRIVING";
          var curLat = <?php echo $currentLocLat; ?>;
          var curLong1 = <?php echo $currentLocLong; ?>;
          initial = new google.maps.LatLng(curLat,curLong1);
          ending = new google.maps.LatLng(dLatitude,dLongitude);


          showRoute(
              DisplayDirections, directionsService, markers, stepDisplay, map,travelMode,initial,ending);
        };
        var shiftcenter = function(e)
        {
          id_address = e.target.id;
          //alert(id_address);
          //console.log(id_address);
          DisplayDirections.setDirections({routes: []});
          if(id_address.indexOf('address') != -1)
          {
            for (var i = 0; i < markers.length; i++) {
              markers[i].setMap(null);
            }
            indAd = id_address.indexOf('address');
            pid =id_address.substring(0,indAd);
            lati_place = pid+'latip';
            lati = document.getElementById(lati_place).innerHTML;
            long_place = pid+'longip';
            longi = document.getElementById(long_place).innerHTML;
            map.setCenter(new google.maps.LatLng(lati, longi));
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(lati, longi),
            map: map
            });
            markers.push(marker);

          }
        }
 
    document.getElementById('walking').addEventListener('click',onChangeHandler);
    document.getElementById('biking').addEventListener('click',onChangeHandler);
    document.getElementById('driving').addEventListener('click',onChangeHandler);
    document.getElementById('response').addEventListener('click',shiftcenter);
            
      }

      function showRoute(DisplayDirections, directionsService,
          markers, stepDisplay, map,mode,initial,ending) {

        // Remove existing markers from map
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(null);
        }

        //Fetch starting and ending locations
        directionsService.route({
          origin: initial,
          destination: ending,
          travelMode: mode
        }, function(response, status) {
          /*
           Creating markers
          */
          if (status === 'OK') {
            document.getElementById('ERROR').innerHTML =
                '<b>' + response.routes[0].warnings + '</b>';
            DisplayDirections.setDirections(response);
          } else {
            window.alert('Directions failed status ' + status);
          }
        });
      }

    </script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuVEKl8_oFsdq7b7_WGG4qi0dCbQ9K6dE&callback=initMap">

    </script>





<script>

//Retain form values
keyword_retain_data = <?php 
if(!isset($_POST['keyword']))
 echo "''";
else
  echo json_encode($_POST['keyword']); ?>;
document.getElementById("keyword").value = keyword_retain_data;

location_retain_data = <?php 
if(!isset($_POST['location']))
 echo "''";
else
  echo json_encode($_POST['location']); ?>;
document.getElementById("location").value = location_retain_data;

distance_retain_data = <?php 
if(!isset($_POST['Distance']))
 echo "''";
else
  echo json_encode($_POST['Distance']); ?>;
document.getElementById("dist").value = distance_retain_data;

category_retain_data = <?php 
if(!isset($_POST['items']))
 echo "''";
else
  echo json_encode($_POST['items']); ?>;
if(category_retain_data != '')
document.getElementById(category_retain_data).selected = true;




here_retain_data = <?php 
if(!isset($_POST['Here']))
 echo "''";
else
  echo json_encode($_POST['Here']); ?>;
if(here_retain_data == "Yes")
document.getElementById("here").checked = "checked";

loc_retain_data = <?php 
if(!isset($_POST['Here']))
 echo "''";
else
  echo json_encode($_POST['Here']); ?>;
if(loc_retain_data == "No")
document.getElementById("locId").checked = "checked";



//Clear current search and form data
el = document.getElementById("clearmyform");
if(el){
  el.addEventListener("click",function(){
  document.getElementById("location").disabled = true;
  document.getElementById("response").innerHTML = '';
  });
}

</script>  



</BODY>
</HTML>




