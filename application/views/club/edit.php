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
            <h3><i class="fa fa-angle-right"></i> Edit Club</h3>
            
            <!-- BASIC FORM ELELEMNTS -->
            <div class="row mt">
             <div style = "color:red" id = "errorCanvas">
          </div>
              <div class="col-lg-12">

                  <div class="form-panel">
                  <?php
                    echo "<form class=\"form-horizontal style-form\" method=\"post\" action = \"/index.php/club/update/". $dbData["club"]["clubId"] ."\"   enctype=\"multipart/form-data\">"
                  ?>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Name</label>
                              <div class="col-sm-10">
                                  <?php
                                      echo "<input value = \"" . $dbData["club"]["clubName"]. "\" name = \"clubName\" type=\"text\" class=\"form-control\">";
                                   ?>
                                  
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Email</label>
                              <div class="col-sm-10">
                              <?php
                                      echo "<input value = \"" . $dbData["club"]["clubEmail"]. "\" name = \"clubEmail\" type=\"text\" class=\"form-control\">";
                                   ?>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Description</label>
                              <div class="col-sm-10">
                               <?php
                                      echo "<textarea  name = \"clubDescription\" type=\"text\" class=\"form-control\">".$dbData["club"]["clubDescription"] ."</textarea>";
                                   ?>
                              </div>

                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Phone Number</label>
                              <div class="col-sm-10">
                               <?php
                                      echo "<input value = \"" . $dbData["club"]["clubPhoneNumber"]. "\" name = \"clubPhoneNumber\" type=\"text\" class=\"form-control\">";
                                   ?>
                              </div>
                          </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Address</label>
                              <div class="col-sm-10">
                              <?php
                                      echo "<input value = \"" . $dbData["club"]["clubAddress"]. "\"name = \"clubAddress\" type=\"text\" class=\"form-control\">";
                                   ?>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Location</label>
                              <div class="col-sm-10">
                              <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                                 <div id = "mapInfo"> </div>
                                 <div id="map"></div>
                                 <button type = "button" onclick = "unsetPin()" class = "btn btn-danger"> Unset Pin </button>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Add Pictures</label>
                              <div class="col-sm-10">
                                <input type="file" class="form-control" name="userFiles[]" id = "userFiles" multiple/>
                                <span style="color:blue">Chose Multiple Files at once</span>
                              </div>
                          </div>

                        <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Remove Pictures</label>
                              <div class="col-sm-10">
                                   <div class="row">
          <?php ;
          foreach ($dbData["urls"] as $key => $value) {
            $urlSplit = explode('.', $value["uploadUrl"]);
            if (substr(end($urlSplit), 0, -1)=== "png" || substr(end($urlSplit), 0, -1)=== "jpg" || substr(end($urlSplit), 0, -1)=== "gif") {
              echo "<div class=\"col-md-2 col-sm-2\" id = pic". $value["uploadId"] . ">";
              echo "<div class=\"product-col\">";
              echo "<div class=\"image\">";
            echo "<img src=" . $value["uploadUrl"] ."style = \"width:120px; height:120px;\" alt=\"product\"  /> ";
          
           echo "</div>";
           echo "<div class=\"caption\">";
           $temp = $value["uploadId"];
           echo "<button onclick = \"removePic($temp)\" type = \"button\" class = \"btn btn-default\"> Remove </button>";
           echo "</div>";
           echo "</div>";
           echo "</div>";
         }

        }


          ?>
          </div>
                              </div>
                          </div>



                          <button class = "btn btn-default"> Update </button>
                      </form>
                  </div>
              </div><!-- col-lg-12-->       
            </div><!-- /row -->
        
            </div><!-- /row -->
            
            
    </section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script>
      var removePics = [];
      function removePic(val) {
        removePics.push(val);
        $("#pic"+ val).remove();

      }
      var markers = [];
      var marker;
      function unsetPin() {
         markers.forEach(function(marker) {
            marker.setMap(null);
          });
        markers = [];
      }
        function initAutocomplete() {
          if (isNaN(<?php echo $dbData["club"]["clubLat"] ?> )) {
            var uluru = {lat: -33.8688, lng: 151.2195};
          } else {
          var uluru = {lat: parseFloat("<?php echo $dbData["club"]["clubLat"] ?>"), lng: parseFloat("<?php echo $dbData["club"]["clubLng"] ?>")};
        }
        var map = new google.maps.Map(document.getElementById('map'), {

          center: uluru,
          zoom: 13,
          mapTypeId: 'roadmap'
        });
        if (!isNaN(<?php echo $dbData["club"]["clubLat"] ?> )) {
         markers.push(new google.maps.Marker({
              map: map,
              icon: 'https://www.google.com/mapfiles/marker_green.png',
              position: uluru
            }));
       }
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
            clubData.append('removePics', removePics.toString());
//             for (var pair of clubData.entries()) {
//     alert(pair[0]+ ', ' + pair[1]); 
// }
            $.ajax({
              type: 'post',
              url: '/index.php/club/update/'+ <?php echo $dbData["club"]["clubId"] ?> ,
              processData: false,
              contentType: false,
              timeout: 0,
              data: clubData,
              success: function (response) {
                //alert(response.data)
                if (response.result == 1) {
                  window.location = "/index.php/club/show/" + <?php echo $dbData["club"]["clubId"] ?>;
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
      <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd33iPrydbR8cnjSFILr6Hkxg2R93szw8&libraries=places&callback=initAutocomplete">
    </script>
      <!--main content end-->
      <!--main content end-->