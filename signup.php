<?php 
require_once("DBConnection.php");
include("functions.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <title>Sign Up</title>
  <style>
    h1 {
      text-align: center;
      font-size: 2.5em;
      font-weight: bold;
      padding-top: 1.2em;
    }

    form {
      padding: 40px;
    }

    input,
    textarea {
      margin: 5px;
      font-size: 1.1em !important;
      outline: none;
    }

    input[type=radio],
    select {
      width: max-content;
      padding: 5px;
      margin-top: 20px;
      margin-bottom: 20px;
      margin-left: 30px;
      margin-right: 5px;
    }

    textarea {
      height: 80px;
    }

    #err {
      display: none;
      padding: 1.5em;
      padding-left: 4em;
      font-size: 1.2em;
      font-weight: bold;
      margin-top: 1em;
    }

    .error {
      color: #FF0000;
    }
  </style>

</head>

<body>
<!-- php code -->
  <?php
  $nameErr = $emailErr = $phoneErr = $passwordErr = $repasswordErr = $genderErr = "";
  $username = $email = $phone = $password = $repassword = $gender = "";
  global $validate;

  if(isset($_POST['submit'])){
    if(empty($_POST['username'])){
      $nameErr = "Please Enter Username";
      $validate = false;
    }
    else{
      $username = mysqli_real_escape_string($conn,$_POST['username']);
      $validate = true;
    }

    if(empty($_POST['email'])){
      $emailErr = "Please Enter Email";
      $validate = false;
    }
    else{
      $email = mysqli_real_escape_string($conn,$_POST['email']);
      $validate = true;
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailErr = "Please Enter valid email";
        $validate = false;
      }
    }

    if(empty($_POST['phone'])){
      $phoneErr = "Please Enter Phone Number";
      $validate = false;
    }
    else{
      $phone = mysqli_real_escape_string($conn,$_POST['phone']);
      $validate = true;
      if(strlen($phone) > 10 || strlen($phone) < 10 || !preg_match("/[0-9]/",$phone)){
        $phoneErr = "Please Enter valid Phone Number";
        $validate = false;
      }
    }

    if(empty($_POST['password'])){
      $passwordErr = "Please Enter Password";
      $validate = false;
    }
    else{
      $password = mysqli_real_escape_string($conn,$_POST['password']);
      $validate = true;
    }

    if(empty($_POST['repassword'])){
      $repasswordErr = "Please Enter re-password";
      $validate = false;
    }
    else{
      $repassword = mysqli_real_escape_string($conn,$_POST['repassword']);
      $validate = true;
      if($password !== $repassword){
        $repasswordErr = "Password and Confirm Password don't match";
        $validate = false;
      }
    }

    if(empty($_POST['gender'])){
      $genderErr = "Please Select Gender";
      $validate = false;
    }
    else{
      $gender = mysqli_real_escape_string($conn,$_POST['gender']);
      $validate = true;
    }

    $city = $_POST['city'];
    $dept = $_POST['Department'];
    $type = 'employee';
  
 
    if($validate){
      signup($username,$email,$password,$phone,$repassword,$gender,$city,$dept,$type,$conn);
    }
  }

ini_set('display_errors', true);
error_reporting(E_ALL);
  ?>


  <!-- navbar -->
  <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Employee Leave Management</a>

      <a id="home" href="index.php">Home</a>
    </div>
  </nav>

  <h1>Registration Form</h1>

  <div class="alert alert-danger" id="err" role="alert">
  </div>

  <!--form-->
  <form method="POST" autocomplete="off">

    <!--username-->
    <div class="form-floating mb-3">
      <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>"placeholder="name@example.com">
      <label for="username">Name</label>
      <span class="error"><?php echo $nameErr; ?></span>
    </div>

    <!--Email id-->
    <div class="form-floating mb-3">
      <input class="form-control" type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="Enter your email">
      <label for="email">Email address</label>
      <span class="error"><?php echo $emailErr; ?></span>
    </div>

    <!--Phone No.-->
    <div class="form-floating mb-3">
      <input class="form-control" type="tel" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Enter your Phone no.">
      <label for="phone">Phone No.</label>
      <span class="error"><?php echo $phoneErr; ?></span>
    </div>

    <!--Password.-->
    <div class="form-floating mb-3">
      <input class="form-control" type="password" name="password" id="password" value="<?php echo $password; ?>" placeholder="Enter your password">
      <label for="password">Password</label>
      <span class="error"><?php echo $passwordErr; ?></span>
    </div>

    <!--Confirm Password.-->
    <div class="form-floating mb-3">
      <input class="form-control" type="password" name="repassword" id="confirmPassword" value="<?php echo $repassword ?>" placeholder="Re-Enter password">
      <label for="confirmPassword">Confirm Password</label>
      <span class="error"><?php echo $repasswordErr; ?></span>
    </div>

    <label for="gender">Gender:</label>
    <input type="radio" id="gender" name="gender" <?php if(isset($gender)&&$gender=="Male") echo "checked" ?> value="Male">Male
    <input type="radio" id="gender" name="gender" <?php if(isset($gender)&&$gender=="Female") echo "checked" ?> value="Female">Female
    <input type="radio" id="gender" name="gender" <?php if(isset($gender)&&$gender=="Prefer Not to say") echo "checked" ?> value="Prefer Not to say">Prefer Not to say
    <span class="error"><?php echo $genderErr; ?></span>
    <br>

    <label for="city">City:</label>
    <select id="city" name="city">
      <option>Rajkot</option>
      <option>Ahmedabad</option>
      <option>Gandhinagar</option>
      <option>Banglore</option>
      <option>Hyderabad</option>
      <option>Noida</option>
      <option>Gurugram</option>
      <option>Pune</option>
    </select>

    <br>

    <label>Department : </label>
    <select name="Department">
      <option>Engineering</option>
      <option>IT Support</option>
      <option>HR</option>
      <option>Finance</option>
      <option>Marketing and Sales</option>
    </select>

    <br>

    <input type="submit" name="submit" value="Submit" class="btn btn-outline-success">
  </form>



  <!--Footer-->
  <footer class="footer navbar navbar-expand-lg navbar-light bg-light">
    <div>
      <p class="text-center">&copy; Employee Management System, March 2021</p>
      <p class="text-center">Created By: <strong>Yash Sojitra</strong> and <strong>Darshan Mamtani</strong></p>
    </div>
  </footer>


</body>

</html>