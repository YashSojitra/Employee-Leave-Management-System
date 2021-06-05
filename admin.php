<?php
require_once("DBConnection.php");
session_start();
if(!isset($_SESSION["sess_user"])){
  header("Location: index.php");
}
else{
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
    <title>Admin Panel</title>

    <style>
        h1 {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            padding-top: 1em;
        }

        .mycontainer {
            width: 90%;
            margin: 1.5rem auto;
            min-height: 60vh;
        }

        .mycontainer table {
            margin: 1.5rem auto;
        }
    </style>

</head>

<body>
    <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Employee Leave Management</a>

            <button id="logout" onclick="window.location.href='logout.php';">Logout</button </div>
    </nav>

    <h1>Admin Page</h1>

    <div class="mycontainer">

            <table class="table table-bordered">
                <thead>
                    <th>Employee</th>
                    <th>Leave Application</th>
                    <th>Accept</th>
                    <th>Reject</th>
                </thead>
                <tbody>
                        <!-- loading all leave applications from database -->
                        <?php
                                global $row;
                                $query = mysqli_query($conn,"SELECT * FROM leaves WHERE status='pending'");
                                
                                $numrow = mysqli_num_rows($query);

                               if($query){
                                    
                                    if($numrow!=0){
                                         
                                          while($row = mysqli_fetch_assoc($query)){
                                            echo "<tr>
                                                    <td>{$row['ename']}</td>
                                                    <td>{$row['descr']}</td>
                                                    <td><a href=\"updateStatusAccept.php?eid={$row['eid']}&descr={$row['descr']}\">Accept</a></td>
                                                    <td><a href=\"updateStatusReject.php?eid={$row['eid']}&descr={$row['descr']}\">Reject</a></td>
                                                  </tr>";  
                                          }       
                                    }
                                }
                                else{
                                    echo "Query Error : " . "SELECT * FROM leaves WHERE status='pending'" . "<br>" . mysqli_error($conn);
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