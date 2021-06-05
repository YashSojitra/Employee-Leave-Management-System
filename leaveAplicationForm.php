<?php
require_once("DBConnection.php");
session_start();
global $row;
if(!isset($_SESSION["sess_user"])){
  header("Location: index.php");
}
else{
?>

<?php 
  $reasonErr = $absenceErr = "";
  global $leaveApplicationValidate;
  if(isset($_POST['submit'])){
    if(empty($_POST['absence'])){
      $absenceErr = "Please select absence type";
      $leaveApplicationValidate = false;
    }
    else{
      $arr = $_POST['absence'];
      $absence = implode(",",$arr);
      $leaveApplicationValidate = true;
    }
    
    $reason = mysqli_real_escape_string($conn,$_POST['reason']);
    if(empty($reason)){
      $reasonErr = "Please give reason for the leave in detail";
      $leaveApplicationValidate = false;
    }
    else{
      $absencePlusReason = $absence." : ".$reason;
      $leaveApplicationValidate = true;
    }
    
    $status = "pending";
    
    if($leaveApplicationValidate){
      //for eid
      $username = $_SESSION["sess_user"];
      $eid_query = mysqli_query($conn,"SELECT id FROM users WHERE name='".$username."'");
      
      $row = mysqli_fetch_array($eid_query);
      
      $query = "INSERT INTO leaves(eid, ename, descr, status) VALUES({$row['id']},'{$username}','$absencePlusReason','$status')";
      $execute = mysqli_query($conn,$query);
      if($execute){
        echo 'Leave Application Added!!';
      }
      else{
        echo "Query Error : " . $query . "<br>" . mysqli_error($conn);;
      }
    }
  }
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
  <title>Leave Application</title>
  <style>
    h1 {
      text-align: center;
      font-size: 2.5em;
      font-weight: bold;
      padding-top: 1em;
      margin-bottom: -0.5em;
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

    label {
      margin-top: 2em;
      font-size: 1.1em !important;
    }

    label.form-check-label {
      margin-top: 0px;
    }

    #err {
      display: none;
      padding: 1.5em;
      padding-left: 4em;
      font-size: 1.2em;
      font-weight: bold;
      margin-top: 1em;
    }

    table{
      width: 90% !important;
      margin: 1.5rem auto !important;
      font-size: 1.1em !important;
    }

    .error{
      color: #FF0000;
    }
  </style>

  <script>
    const validate = () => {

      let desc = document.getElementById('leaveDesc').value;
      let checkbox = document.getElementsByClassName("form-check-input");
      let errDiv = document.getElementById('err');

      let checkedValue = [];
      for (let i = 0; i < checkbox.length; i++) {
        if(checkbox[i].checked === true)
          checkedValue.push(checkbox[i].id);
      }

      let errMsg = [];

      if (desc === "") {
        errMsg.push("Please enter the reason and date of leave");
      }

      if(checkedValue.length < 1){
        errMsg.push("Please select the type of Leave");
      }

      if (errMsg.length > 0) {
        errDiv.style.display = "block";
        let msgs = "";

        for (let i = 0; i < errMsg.length; i++) {
          msgs += errMsg[i] + "<br/>";
        }

        errDiv.innerHTML = msgs;
        scrollTo(0, 0);
        return;
      }
    }
  </script>

</head>

<body>
  <!--Navbar-->
  <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Employee Leave Management</a>

      <button id="logout" onclick="window.location.href='logout.php';">Logout</button>
    </div>
  </nav>


  <h1>Leave Application</h1>

  <div class="alert alert-danger" id="err" role="alert">
  </div>

  <form method="POST">
    

    <label>Type of absence requested :</label>
    <!-- error message if type of absence isn't selected -->
    <span class="error"><?php echo "&nbsp;".$absenceErr ?></span><br/>
    <div class="form-check">
      <input class="form-check-input" name="absence[]" type="checkbox" value="Sick" id="Sick">
      <label class="form-check-label" for="Sick">
        Sick
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" name="absence[]" type="checkbox" value="Vacation" id="Vacation">
      <label class="form-check-label" for="Vacation">
        Vacation
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" name="absence[]" type="checkbox" value="Bereavement" id="Bereavement">
      <label class="form-check-label" for="Bereavement">
        Bereavement
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" name="absence[]" type="checkbox" value="Time off without pay" id="Time Off Without Pay">
      <label class="form-check-label" for="Time Off Without Pay">
        Time off without pay
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" name="absence[]" type="checkbox" value="Maternity / Paternity" id="Maternity/Paternity">
      <label class="form-check-label" for="Maternity/Paternity">
        Maternity / Paternity
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" name="absence[]" type="checkbox" value="Other" id="Other">
      <label class="form-check-label" for="Other">
        Other
      </label>
    </div>

    <div class="mb-3">
      
      <label for="leaveDesc" class="form-label">Enter the reason of leave and also dates of leave :</label>
      <!-- error message if reason of the leave is not given -->
      <span class="error"><?php echo "&nbsp;".$reasonErr ?></span>
      <textarea class="form-control" name="reason" id="leaveDesc" rows="3"></textarea>
    </div>


    <input type="submit" name="submit" value="Submit" class="btn btn-outline-success">
  </form>

  <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <th>Leave Application</th>
            <th>Status</th>
        </thead>
        <tbody>
          <!-- loading all leave applications of the user -->
          <?php
                $leaves = mysqli_query($conn,"SELECT descr,status FROM leaves WHERE eid='".$_SESSION['sess_eid']."'");
                if($leaves){
                  $numrow = mysqli_num_rows($leaves);
                  if($numrow!=0){
                    while($row1 = mysqli_fetch_array($leaves)){
                      echo "<tr>
                              <td>{$row1['descr']}</td>
                              <td>{$row1['status']}</td>
                            </tr>";
                    }
                  }
                }
                else{
                  echo "Query Error : " . "SELECT descr,status FROM leaves WHERE eid='".$_SESSION['sess_eid']."'" . "<br>" . mysqli_error($conn);;
                }
            ?>
        </tbody>
    </table>
</div>

  <footer class="footer navbar navbar-expand-lg navbar-light bg-light">
    <div>
      <p class="text-center">&copy; Employee Management System, March 2021</p>
      <p class="text-center">Created By: <strong>Yash Sojitra</strong> and <strong>Darshan Mamtani</strong></p>
    </div>
  </footer>

</body>

</html>

<?php
}

ini_set('display_errors', true);
error_reporting(E_ALL);
?>