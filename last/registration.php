<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="registration.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $Name = $_POST["name"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($Name) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
          }

          else{
            require_once "database.php";
            $sql = "INSERT INTO users (name, email, password) VALUES ( ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$Name, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            }else {
              die("Error: check " . mysqli_error($conn)); // Display detailed error message
          }
            }
           }
        

        

           ?>

      
           <form action="registration.php" method="post">

           <p class="title">Register </p>
           <p class="message">Please fill the information </p>
               <div class="form-group">
               <label>
                   <input type="text" class="form-control" name="name" placeholder="Full Name:">
        </label>
               </div>
               <br>
               <div class="form-group">
                <label>
                   <input type="emamil" class="form-control" name="email" placeholder="Email:">
        </label>
               </div>
               <br>
               <div class="form-group">
                <label>
                   <input type="password" class="form-control" name="password" placeholder="Password:">
        </label>
               </div>
               <br>
               <div class="form-group">
                <label>
                   <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
        </label>
               </div>
               <br>
               <div class="form-btn">
                   <input type="submit" class="btn" value="Register" name="submit">
               </div>
           </form>
           <p class="signin">Already have an account ? <a href="login.php">Login</a> </p>
       </div>
       <img  class="Bg-image" src="images/back4.jpg" alt="Bg-image" >
      
   </body>
   </html>