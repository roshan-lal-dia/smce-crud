<?php
// Include config file
require_once "../db-conn.php";
 
// Define variables and initialize with empty values
$firstname = $lastname= $email = $password = $confirm_password = "";
$firstname_err = $lastname_err = $email_err =  $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate firstname
    $input_firstname = trim($_POST["firstname"]);
    if(empty($input_firstname)){
        $firstname_err = "Please enter your First name.";
    } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "First name can only contain letters.";
    } else{
        $firstname = $input_firstname;
    }
    
    // Validate lastname
    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter your Last name.";     
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["lastname"]))){
        $lastname_err = "Last name can only contain letters.";
    }  
    else{
        $lastname = $input_lastname;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter your email.";     
    } elseif(!preg_match('/^([a-zA-Z0-9-_.]+)@([a-zA-Z0-9-_.]+)(\.[a-zA-Z]+)(\.[a-zA-Z])?/', trim($_POST["email"]))){
        $email_err = "Invalid email format ,eg - abc@fff.com.";
    } else{
        $email = $input_email;
    }
    
//try regex
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (firstname, lastname, email, password1) VALUES (?, ?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_firstname, $param_lastname, $param_email, $param_password1);
            
            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_email = $email;
            $param_password1 = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: ../dashboard/admin.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    <style> 
          a {
            text-decoration: none 
}
    </style>
</head>
<body>
<section class="vh-100">
        <div class="container h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
              <div class="card text-black" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                  <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                           <!-- form-->
                    <form class="mx-1 mx-md-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h3 class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Create User</h3>
                    <p class="text-center p fw-bold mb-5 mx-1 mx-md-4 mt-4">Enter Credentials for new user</p>
                    <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                          <label class="form-label" for="form3Example1c" >First Name</label> 
                            <input type="text" id="form3Example1c" name = "firstname" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>"  value="<?php echo $firstname; ?>">
                            <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
                          </div>
                        </div>
                        <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                          <label class="form-label" for="form3Example1c">Last Name</label>
                            <input type="text" id="form3Example1c" name = "lastname"  class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
                            <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
                          </div>
                        </div>
                        <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                            <label class="form-label" for="form3Example3c">Email</label>
                            <input type="email" id="form3Example3c" name = "email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err; ?></span>
                          </div>
                        </div>
                        <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                          <label class="form-label" for="form3Example4c">Password</label>
                            <input type="password" id="form3Example4c" name = "password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                          </div>
                        </div>
                        <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                          <label class="form-label" for="form3Example4cd">Confirm  password</label>
                            <input type="password" id="form3Example4cd"  name="confirm_password"  class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                          </div>
                        </div>
                          <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                          <button type="submit" class="btn btn-primary btn-lg" value = "submit" name="Create">Create</button>
                        </div>
                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                        <a href="../dashboard/admin.php" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                     </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>   
</body>
</html>