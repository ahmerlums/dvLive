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
         
          	<h3><i class="fa fa-angle-right"></i> Create Ad</h3>
          	
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
                                  <input name = "adName" type="text" class="form-control">
                              </div>
                          </div>
                           <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Start Date</label>
                              <div class="col-sm-10">
                              <input type="date" name="startDate" class="form-control">
                              </div>
                          </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">End Date</label>
                              <div class="col-sm-10">
                              <input type="date" name="endDate" class="form-control">
                              </div>
                          </div>


                           <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Status</label>
                              <div class="col-sm-10">
                                  <input name = "adStatus" type="text" class="form-control">
                              </div>
                          </div>


                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Link</label>
                              <div class="col-sm-10">
                                  <input name = "adLink" type="text" class="form-control">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Picture</label>
                              <div class="col-sm-10">
                                <input type="file" class="form-control" name="userFiles[]" id = "userFiles" multiple/>
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

            var adData = new FormData($(this)[0]);
            if (markers.length == 1) {
                adData.append('adLat', markers[0].getPosition().lat());
                adData.append('adLng', markers[0].getPosition().lng());

            }  else {
               adData.append('adLat', "");
                adData.append('adLng', "");

            }  
            $.ajax({
              type: 'post',
              url: '/index.php/ad/create',
              processData: false,
              contentType: false,
              timeout: 0,
              data: adData,
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
        
        });

      });
      </script>

      <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd33iPrydbR8cnjSFILr6Hkxg2R93szw8&libraries=places&callback=initAutocomplete">
    </script>



     