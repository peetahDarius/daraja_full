<?php
include('./includes/header.php');

?>



  <body oncontextmenu="return false" class="snippet-body">
    <div class="container d-flex justify-content-center">
      <div class="card mt-5 px-3 py-4">
        <div class="d-flex flex-row justify-content-around">
        <?php
            if (isset($_GET['error'])) {
               
               if ($_GET["error"] == "emptyinput") {
                  echo "<p>Fill in all fields 😆 </p>";
               }
               else if($_GET["error"] == "invalidemail"){
                  echo "<p>Choose a valid email 😪 </p>";
               } 
               else if($_GET["error"] == "passnotsame"){
                  echo "<p>Passwords don't match 🙄</p>";
               }
               else if($_GET["error"] == "usernametaken"){
                  echo "<p>That username is taken 😂😅</p>";
               }
               else if($_GET["error"] == "none"){
                  echo "<p>Successfully registered 🎉</p>";
               }
            }
        ?>
          <div class="mpesa">
            <a href="./login.php" style="color: white;text-decoration:none;">
              <span>Login</span>
            </a>
          </div>
          <div class="records-tab"> <a class="records-tab" href="./records.php" style="text-decoration:none; color:grey;"> <span>Records</span> </a>  </div>
          <div style="display:none;"><span>Card</span></div>
        </div>
        <div class="media mt-4 pl-2">
        </div>
        <div class="media mt-3 pl-2">
                          <!--bs5 input-->

            <form class="row g-3" action="./includes/signup.inc.php" method="POST">
            
                <div class="col-12">
                  <label for="inputAddress" class="form-label">First Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Full Name..">
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label" >Email address</label>
                  <input type="email" class="form-control" name="email"  placeholder="Enter Email">
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label" >Username</label>
                  <input type="text" class="form-control" name="uid"  placeholder="Enter Username">
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label" >Password</label>
                  <input type="password" class="form-control" name="pwd"  placeholder="Enter Password">
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label" >Username</label>
                  <input type="password" class="form-control" name="pwd_repeat"  placeholder="Confirm Password">
                </div>
             
                <div class="col-12">
                  <button type="submit" class="btn btn-success" name="submit" value="submit">Create Account</button>
                </div>
              </form>
              <!--bs5 input-->
          </div>
        </div>
      </div>
    </div>


<?php
include('./includes/footer.php');
?>