      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
          	<h3><i class="fa fa-angle-right"></i> Listing Admins</h3>
		  		<div class="row mt">
			  		<div class="col-lg-12">
                      <div class="content-panel">
                          <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                              <thead>

                              <tr>
                                  <th>Name</th>
                                  <th>Email</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php 
                                foreach ($allAdmins as $adminRecord) {
                                  echo "<tr>";
                                  echo "<td class =>" . $adminRecord["adminName"] .  "</td>";
                                  echo "<td class =>" . $adminRecord["adminEmail"] .  "</td>";
                                  echo "</tr>";
                                }
                              ?>
                             
                              </tbody>
                          </table>

                          <a href = "/index.php/admin/view/signup" class = "btn btn-default"> Create Admin</a>
                          </section>
                  </div><!-- /content-panel -->
               </div><!-- /col-lg-4 -->			
		  	</div><!-- /row -->
		  	

		</section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->