<?php 
    require_once("DBConnection.php");
?>

<?php

    function encryption ($password){
        $BlowFishFormate = "$2y$10$";
        $salt = generateSalt(22);
        $BlowFish_Plus_Salt = $BlowFishFormate . $salt;
        $Hash = crypt($password, $BlowFish_Plus_Salt);

        return $Hash;
    }

    function generateSalt($length){
        $uniqueRandomString = md5(uniqid(mt_rand(), true));
        $base64String = base64_encode($uniqueRandomString);
        $modifiedBase64String = str_replace('+','.',$base64String);
        $salt = substr($modifiedBase64String,0,$length);

        return $salt;
    }

    function passwordCheck($password, $existingHash){
        $Hash = crypt($password, $existingHash);
        if($Hash === $existingHash)
            return true;
        else
            return false;
    }

    function login($username, $password, $conn){
            $query = mysqli_query($conn, "SELECT * FROM users WHERE name='".$username."'");
			$numrows = mysqli_num_rows($query);
			if($numrows !=0)
			{
				while($row = mysqli_fetch_assoc($query))
				{
					$dbusername=$row['name'];
					$dbpassword=$row['password'];
					$type=$row['type'];
					$id=$row['id'];
				}
				if($username == $dbusername && passwordCheck($password, $dbpassword))
				{
					
					$_SESSION['sess_user']=$username;
					$_SESSION['sess_eid']=$id;
					//Redirect Browser
					if($type=="admin"){
						header("Location:admin.php");
					}
					else{
					header("Location:leaveAplicationForm.php");
					}
                    return true;
				}
			}
			else{
	 			//echo "Invalid Username or Password";
                 return false;
                 
	 		}
    }

    function signup($name,$email,$password,$phone,$repassword,$gender,$city,$dept,$type,$conn){
        $hashedPassword = encryption($password);

        $query = mysqli_query($conn,"INSERT INTO users(name, email, phone, password, gender, city, department, type) VALUES('$name','$email','$phone','$hashedPassword','$gender','$city','$dept','$type')");
        $query1 = mysqli_query($conn,"SELECT id from users WHERE name='".$name."'");
        $eid = mysqli_fetch_assoc($query1);

        if($query){


            echo 'Registration successful!!';
            
            $_SESSION['sess_user'] = $name;
            $_SESSION['sess_eid'] = $eid['id'];

            header("Location:leaveAplicationForm.php");
            exit;
        }
        else{
            echo "Query Error : " . "INSERT INTO users(name, email, phone, password, gender, city, department, type) VALUES('$name','$email','$phone','$hashedPassword','$gender','$city','$dept','$type')" . "<br>" . mysqli_error($conn);
            echo "<br>";
            echo "Query Error : " . "SELECT id from users WHERE name='".$name."'" . "<br>" . mysqli_error($conn);
        }

    }

?>