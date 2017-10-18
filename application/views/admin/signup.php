<section id="main-content">
          <section class="wrapper">
          	<h3><i class="fa fa-angle-right"></i> Register</h3>
          	
          	<!-- BASIC FORM ELELEMNTS -->
          	<div class="row mt">
          		<div class="col-lg-12">
              <div style = "color:red" id = "errorCanvas">
              </div>
                  <div class="form-panel">
                      <form class="form-horizontal style-form" method="post">
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Admin Name</label>
                              <div class="col-sm-10">
                                  <input name = "adminName" type="text" class="form-control">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Admin Email</label>
                              <div class="col-sm-10">
                                  <input name = "adminEmail" type="text" class="form-control">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Password</label>
                              <div class="col-sm-10">
                                  <input name = "password" class="form-control" id="form-control" type="password" >
                              </div>
                          </div>
                               <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Confirm Password</label>
                              <div class="col-sm-10">
                                  <input  name = "confirmpassword" class="form-control" id="form-control" type="password" >
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
       $(function () {

        $('form').on('submit', function (e) {
          e.preventDefault();

          $.ajax({
            type: 'post',
            url: '/index.php/admin/create',
            data: $('form').serialize(),
            success: function (response) {
              if (response.result == 1) {
                window.location = "/index.php/admin/view/index";
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
      <!--main content end-->