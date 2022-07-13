<?php
    session_start();
    include 'db.php';
    
    if(!empty($_POST['login-username'])){
        $query =    "SELECT * FROM tbl_users_202 
                    WHERE user_name='" . $_POST['login-username']
                    . "' and password = '" 
                    . $_POST['login-pass']
                    ."'";

        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_array($result);
        
    
        if(is_array($row)) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['pass'] = $row['name'];
            $_SESSION['type'] = $row['user_type'];
            if(isset($_SESSION['id'])){
                if($_SESSION['type'] == 1){
                    header('Location:' . URL . 'parentUserHomePage.php');
                } elseif($_SESSION['type'] == 2){
                    header('Location:' . URL . 'childUserHomePage.php');
                }
           }
        } else {
            $message = "Invalid Username or Password";
        }
    } 
    unset($_POST);
?>









<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$tel    = "";
$address= "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'nadir', 'password', 'salimma_Customers');

// REGISTER USER
if (isset($_POST['user_id'])) {
  // receive all input values from the form
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);


  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($email)) { array_push($errors, "Must enter password"); }
  if (empty($password_1)) { array_push($errors, " Must enter email "); }
  if ($password_1 != $password_2) {array_push($errors, "!הסיסמאות לא תואמות");  }


  
  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM div_213_users_new WHERE username='$username' OR email='$email' OR  LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] !=== $email) {
      array_push($errors, " There is no email in this app!");
    }
    if ($user['email'] === $email) {
      array_push($errors, "The email is exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (email, password) 
  			  VALUES( '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['email'] = $email;
  	$_SESSION['success'] = "welcome to mealy";
  	header('location: homepage.php');
  }
}

if (isset($_POST['id_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
  	array_push($errors, "Must enter password");
  }
  if (empty($password)) {
  	array_push($errors, "wrong password");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "welcome to mealy";
  	  header('location: home.php');
  	}else {
  		array_push($errors, "!טעות בשם המשתמש/הסיסמא");
  	}
  }
}

?>