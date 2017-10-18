<?php
if ($this->session->userdata('adminName') != "") {
    if ($this->router->fetch_class() == "admin") {
        $adminActive = " <a class=\"active\" href=\"/index.php/diversvisiblity/admin/index\">
                          <i class=\"fa fa-dashboard\"></i>
                          <span>Admin</span>
                  </a>";
        $clubActive  = " <a  href=\"/index.php/diversvisiblity/club/index\">
                          <i class=\"fa fa-dashboard\"></i>
                          <span>Club</span>
                      </a>";
    } else {
        $adminActive = " <a  href=\"/index.php/diversvisiblity/admin/index\">
                          <i class=\"fa fa-dashboard\"></i>
                          <span>Admin</span>
                  </a>";
        $clubActive  = " <a  class=\"active\" href=\"/index.php/diversvisiblity/club/index\">
                          <i class=\"fa fa-dashboard\"></i>
                          <span>Club</span>
                      </a>";
        
        
        
    }
    
    echo "<aside>
          <div id=\"sidebar\"  class=\"nav-collapse \">
              <!-- sidebar menu start-->
              <ul class=\"sidebar-menu\" id=\"nav-accordion\">
              
                    <p class=\"centered\"><a href=\"profile.html\"><img src=\"assets/img/ui-sam.jpg\" class=\"img-circle\" width=\"60\"></a></p>

                    <h5 class=\"centered\"> " . $this->session->userdata('adminName') . " </h5>
                        
                  <li class=\"mt\">" . $adminActive . "</li>

                   <li class=\"mt\">" . $clubActive . "
                      
                  </li>



              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>";
}
?>