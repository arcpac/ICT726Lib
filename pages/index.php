<?php
session_start();
$server_name = 'localhost';
$username_server = 'root';
$password_server = '';
$database_name = 'library-guard';

$connection = mysqli_connect($server_name, $username_server, $password_server, $database_name);

if (!$connection) {
  die('Connection unsuccessful' . mysqli_connect_error());
}

$username = '';
$email = '';
$admin = 'user';
$errors = array();

if (isset($_POST['register-user'])) {
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $email = mysqli_real_escape_string($connection, $_POST['email']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);
  $password2 = mysqli_real_escape_string($connection, $_POST['password2']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }
  if ($password != $password2) {
    array_push($errors, "The two passwords do not match");
  }

  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($connection, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($username) {
    if ($user['username'] === $username) {
      array_push($errors, "Username exists.");
    }
  }

  if ($email) {
    if ($user['email'] === $email) {
      array_push($errors, "Email exists.");
    }
  }

  if (!isValidPassword($password)) {
    array_push($errors, "Your password needs to: <br> • include both lower and upper case characters<br/>• include at least one number or symbol<br/>• be at least 8 characters long ");
  }

  $email = test_input($_POST["email"]);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "Invalid email format");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    // admin registration
    if (isset($_POST['user_type'])) {
      $admin = mysqli_real_escape_string($connection, $_POST['admin']);
      $user_type = escape_string($_POST['user_type']);
      $query = "INSERT INTO users (username, email, password, user_type, active) 
  			  VALUES('$username', '$email', '$password', 'admin', 1)";
      // insert
      if (mysqli_query($connection, $query)) {
        $_SESSION['user'] = getUserById($logged_in_user_id);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Welcome Admin!";
        header('location: ../admin/admin_dashboard.php');
      } else {
        echo "Register Error: " . $query . mysqli_error($connection);
      }
    } else {
      // user registration 
      $query = "INSERT INTO users (username, email, password, active) 
  			  VALUES('$username', '$email', '$password', 1)";
      if (mysqli_query($connection, $query)) {
        $logged_in_user_id = mysqli_insert_id($connection);
        $_SESSION['user'] = getUserById($logged_in_user_id);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: ../user/index.php');
      } else {
        echo "Register Error: " . $query . mysqli_error($connection);
      }
    }
  }
}

function escape_string($val)
{
  global $connection;
  return mysqli_real_escape_string($connection, trim($val));
}

function getUserById($id)
{
  global $connection;
  $query = "SELECT * FROM users WHERE id=" . $id;
  $result = mysqli_query($connection, $query);

  $user = mysqli_fetch_assoc($result);
  return $user;
}

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function isValidPassword($password)
{
  if (!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password))
    return FALSE;
  return TRUE;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.101.0">
  <title>Register | Helios Library</title>
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.png">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
  <link href="../assets/css/register.css" rel="stylesheet">
</head>

<body style="background-image: linear-gradient(rgba(31, 55, 99, 0.45), rgba(31, 55, 99, 0.45)), url('../assets/images/library.jpeg'); background-size: cover;">
  <div class="registration-form text-center">
    <main>
      <form class="needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" novalidate>
        <img class="mb-4" src="../assets/images/logo.png" alt="Helios Logo" height="175">
        <?php include('errors.php'); ?>
        <div class="form-group">
          <label for="username" class=visually-hidden>Username</label>
          <input type="text" pattern="[A-Za-z0-9]+.{4,50}" class="form-control item" id="username" name="username" placeholder="Username" onkeyup='Validate(this.id,"text");' required>
          <small class="form-text text-muted">Username must be between 5 and 50 characters in length.</small>
          <div class="invalid-feedback">
            Please choose a valid username.
          </div>
        </div>

        <div class="form-group">
          <label for="email" class="visually-hidden">Email</label>
          <input type="email" class="form-control item" id="email" name="email" placeholder="you@example.com" required>
          <div class="invalid-feedback">
            Please enter a valid email address.
          </div>
        </div>

        <div class="form-group">
          <label for="username" class="visually-hidden">Password</label>
          <input type="password" class="form-control item" id="password" name="password" placeholder="Enter password" required>
          <small class="form-text text-muted">Your password must be at least 8 characters long, must contain special characters "!@#$%&*_?", numbers, lower and upper letters only.</small>
          <div class="invalid-feedback">
            Password is required.
          </div>
        </div>

        <div class="form-group">
          <label for="username" class="visually-hidden">Confirm Password</label>
          <input type="password" class="form-control item" id="password2" name="password2" placeholder="Confirm password" required>
          <div class="invalid-feedback">
            Confirm password.
          </div>
        </div>
        
        <div class="form-group">
          <input class="w-100 btn btn-lg item" name="register-user" value="Register" type="submit" style="background-color: #ff8e00; color: white">
        </div>
      </form>
      <div class="social-media">
        <h5 style="color: #ff8e00">Have an account already?</h5>

        <label>
          <a href="../index.php">Login</a>
        </label>

      </div>
    </main>
  </div>

  <footer class="my-5 pt-5 text-white text-center text-small">
    <p class="mb-1">All Rights Reserved &copy; 2022 Helios Library</p>
    <p class="mb-1">Created by KC Marie Arce, Antonio Caballes, Diego Cardoso de Moura</p>
  </footer>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function(form) {
          form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }

            form.classList.add('was-validated')
          }, false)
        })
    })()

    function Validate(id, numType) {
      var thisId = document.getElementById(id);
      if (numType == "text") {
        thisId.value = thisId.value.replace(/[^0-9a-zA-Z]+/g, '');
      }
    }
  </script>
</body>

</html>