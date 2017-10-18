      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
            <h3><i class="fa fa-angle-right"></i> Listing Clubs</h3>
          <div class="row mt">

            <div class="col-lg-12">
                      <div class="content-panel">
                          <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                              <thead>

                              <tr>
                                  <th style="text-align:center;" >Name</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php 
                                foreach ($dbData as $clubRecord) {
                                  echo "<tr>";
                                  echo "<td style=\"text-align:center\">  <a href = /index.php/club/show/" . $clubRecord["clubId"]. "> ". $clubRecord["clubName"] .  "</a> </td>";
                                }
                              ?>
                             
                              </tbody>
                          </table>

                          <a style="margin-left: 46%;" href = "/index.php/club/view/create" class = "btn btn-primary"> Create Club</a>
                          </section>
                  </div><!-- /content-panel -->
               </div><!-- /col-lg-4 -->     
        </div><!-- /row -->
        

    </section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->