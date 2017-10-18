    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 250px;
        width:60%;

      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }

      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
      }
      #target {
        width: 345px;
      }
    </style>

<section id="main-content">
          <section class="wrapper">
         
          	<h3><i class="fa fa-angle-right"></i> Create Club</h3>
          	
          	<!-- BASIC FORM ELELEMNTS -->
          	<div class="row mt">
             <div style = "color:red" id = "errorCanvas">
          </div>
          		<div class="col-lg-12">

                  <div class="form-panel">
                      <form class="form-horizontal style-form" method="post" enctype="multipart/form-data" >
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Name</label>
                              <div class="col-sm-10">
                                  <input name = "clubName" type="text" class="form-control">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Email</label>
                              <div class="col-sm-10">
                                  <input name = "clubEmail" type="text" class="form-control">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Description</label>
                              <div class="col-sm-10">
                              <textarea name="clubDescription" class = "form-control"></textarea>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Phone Number</label>
                              <div class="col-sm-10">
                                  <input name = "clubPhoneNumber" type="text" class="form-control">
                              </div>
                          </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Address</label>
                              <div class="col-sm-10">
                                  <input name = "clubAddress" type="text" class="form-control">
                              </div>
                          </div>

                           <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Location</label>
                              <div class="col-sm-10">
                              <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                                 <div id = "mapInfo"> </div>
                                 <div id="map"></div>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Pictures</label>
                              <div class="col-sm-10">
                                <input type="file" class="form-control" name="userFiles[]" id = "userFiles" multiple/>
                                <span style="color:blue">Chose Multiple Files at once</span>
                              </div>

                          </div>

                          
                          <input class = "btn btn-default" type="submit">
                      </form>
                  </div>
          		</div><!-- col-lg-12-->      	
          	</div><!-- /row -->
        
          	</div><!-- /row -->
          	
          	
		</section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->
      <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script>
      var markers = [];
      var marker;

        function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
         
        
        map.addListener('click', function(ev) {

          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];
          markers.push(new google.maps.Marker({
              map: map,
              icon: 'https://www.google.com/mapfiles/marker_green.png',
              position: ev.latLng
            }));
       

        });
       

        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
           markers = [];

  
          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
          var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };


            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));


            markers[markers.length - 1].addListener('click', function(ev) {
           
                markers.forEach(function(marker) {
                  marker.setMap(null);
                });
                markers = [];

                markers.push(new google.maps.Marker({
                    map: map,
                    icon: 'https://www.google.com/mapfiles/marker_green.png',
                    position: ev.latLng
                  }));
            });

            if (place.geometry.viewport) {
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
           if (markers.length > 1) {
                $("#mapInfo").html("Your seach points to multiple locations. Please click to select one.");
            } else {
              $("#mapInfo").html("");
            }
        });
      }


       $(function () {

      $("form").on('keypress' , function(e) {
          var key = e.charCode || e.keyCode || 0;     
          if (key == 13) {
            e.preventDefault();
          }
      });
        $('form').on('submit', function (e) {
          e.preventDefault();
          if (markers.length <= 1 ) {

            var clubData = new FormData($(this)[0]);
            if (markers.length == 1) {
                clubData.append('clubLat', markers[0].getPosition().lat());
                clubData.append('clubLng', markers[0].getPosition().lng());
            }  else {
               clubData.append('clubLat', "");
                clubData.append('clubLng', "");

            }   

            $.ajax({
              type: 'post',
              url: '/index.php/club/create',
              processData: false,
              contentType: false,
              timeout: 0,
              data: clubData,
              success: function (response) {
                if (response.result == 1) {
                  window.location = "/index.php/club/index";
                }
                var errorArray = response.data.split('\n');
                var insertDiv = "<ol>";
                for (i of errorArray) {
                  if (i != "") {
                      insertDiv = (insertDiv + "<li>" + i + "</li>")
                  }
                }
                insertDiv = insertDiv + "</ol>";
                $("#errorCanvas").html(insertDiv);

            }
          });
          } else {
            insertDiv = ( "<ol> <li> Click to select only one location! </li> </ol>")
             $("#errorCanvas").html(insertDiv);
          }
        });

      });
      </script>
      <script src="https://s3.us-east-2.amazonaws.com/diversvisiblity/poacode.js">
      </script>
<!--       <script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//192.168.100.6/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
   // alert(g.src);
  })();


</script> -->
<script type="text/javascript">
$(document).ready(function() {

      var nameEQ = "ci_session=";
  //  var ca = document.cookie.split(';');
    console.log(document.cookie);
  //  document.cookie ="_poi_id.1.1107=7c0534d71ea0572c.1508237761.1.1508238027.1508237761.; _poi_ses.1.1107=*"
    //document.cookie.split(';');
    // for(var i=0;i < ca.length;i++) {
    //     var c = ca[i];
    //     alert(c)
    //     while (c.charAt(0)==' ') c = c.substring(1,c.length);
    //     if (c.indexOf(nameEQ) == 0) alert(c.substring(nameEQ.length,c.length));
    // }
});
</script>
<!-- End Piwik Code -->
      <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd33iPrydbR8cnjSFILr6Hkxg2R93szw8&libraries=places&callback=initAutocomplete">
    </script>
      <!--main content end-->