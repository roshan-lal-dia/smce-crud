<?php
//user login
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../dashboard/admin.php");
    exit;
}
 
// Include config file
require_once "db-conn.php";
 
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter the email used to register.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, email, password1 FROM admins WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $email, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            // Redirect user to welcome page
                            header("location: ../dashboard/admin.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid password.";
                        }
                    }
                } else{
                    // email doesn't exist, display a generic error message
                    $login_err = "Invalid email .";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    <!--icons-->
    <link href="../dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <style> 
    .login_oueter {
    width: 360px;
    max-width: 100%;
}
.logo_outer{
    text-align: center;
}
.logo_outer img{
    width:120px;
    margin-bottom: 40px;
}

.fas {
    font-size:33px;
}
</style>
</head>
<body>
<div class="container-fluid">
  <div class="row d-flex justify-content-center align-items-center m-0" style="height: 100vh;">
    <div class="login_oueter">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="login" autocomplete="off" class="bg-light border p-3">
        <div class="form-row">
          <h4 class="title my-3">Login For Admin Access</h4>
          <?php 
             if(!empty($login_err)){
                 echo '<div class="alert alert-danger">' . $login_err . '</div>';
                 }        
                ?>
                <img src="../dashboard/img/smec.png" class="img-fluid" alt="stella marys college of engineering">
                <hr style="height:2px; width:50%; border-width:0; background-color:#254291">
          <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
              </div>
              <input name="email" type="text" value="<?php echo $email; ?>" class="input form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email"  aria-label="Username" aria-describedby="basic-addon1" />
              <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
          </div>
          <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
              </div>
              <input name="password" type="password"  class="input form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password"  required="true" aria-label="password" aria-describedby="basic-addon1" />
              <span class="invalid-feedback"><?php echo $password_err; ?></span>
              <div class="input-group-append">
                <span class="input-group-text" onclick="password_show_hide();">
                  <i class="fas fa-eye" id="show_eye"></i>
                  <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="col-sm-12 pt-3 text-right">
            <p>Not yest registered? <a href="register.php">Register</a></p>
          </div>
          <div class="col-12">
            <button class="btn btn-primary" type="submit" name="submit">Login</button>
          </div>
        </div>
      </form>
      <hr class="mt-4">
        <div class="col-12">
         <p class="text-center mb-0">Forgot Password? <a href="reset-password.php">Reset Password</a></p>
        </div>
    </div>
  </div>
</div>
    <script> 
    function password_show_hide() {
  var x = document.getElementById("password");
  var show_eye = document.getElementById("show_eye");
  var hide_eye = document.getElementById("hide_eye");
  hide_eye.classList.remove("d-none");
  if (x.type === "password") {
    x.type = "text";
    show_eye.style.display = "none";
    hide_eye.style.display = "block";
  } else {
    x.type = "password";
    show_eye.style.display = "block";
    hide_eye.style.display = "none";
  }
}
</script>
    <!-- Bootstrap JS -->
    <script src="https://www.markuptag.com/bootstrap/5/js/bootstrap.bundle.min.js"></script>
</body>
</html>