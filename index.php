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
// ===================
$username = '';
$errors = array();
$_SESSION['ATTEMPTS'] = 3;

if (isset($_POST['login-user'])) {
    $time = time() - 30;
    $ip_address = getIpAddr();
    // Getting total count of hits on the basis of IP
    $query = mysqli_query($connection, "select count(*) as total_count from loginlogs where TryTime > $time and IpAddress='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];

    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $logging_user = "SELECT * FROM users WHERE active = 1 and username = '$username'";
    if ($user_result = mysqli_query($connection, $logging_user)) {
        $logging_user = mysqli_fetch_array($user_result);
        if (isset($logging_user['id'])) {
            $user_id = $logging_user['id'];
        }
    }
    //Checking if the attempt 3, or youcan set the no of attempt her. For now we taking only 3 fail attempted
    if ($total_count == 3) {
        array_push($errors, "Too many failed login attempts. <br/>Please login after 30 sec (Error code: 037)");
    } else {

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $results = mysqli_query($connection, $query);
            $logged_in_user = mysqli_fetch_assoc($results);

            if (mysqli_num_rows($results) == 1 && $logged_in_user['active'] == 1) {
                $_SESSION['IS_LOGIN'] = 'yes';
                mysqli_query($connection, "delete from loginlogs where IpAddress='$ip_address'");
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['username'] = $logged_in_user['username'];
                $_SESSION['success'] = "You are now logged in";
                if ($_SESSION['user']['user_type'] == 'user') {
                    header('location: user/index.php');
                }
                if ($_SESSION['user']['user_type'] == 'admin') {
                    header('location: admin/index.php');
                }
            } elseif (mysqli_num_rows($results) == 1 && $logged_in_user['active'] == 0) {
                array_push($errors, "User is disabled. Please contact administrator. (Error code: 065)");
            } else {
                $total_count++;
                $rem_attm = 3 - $total_count;
                if ($rem_attm == 0) {
                    array_push($errors, "Too many failed login attempts. <br/>Please login after 30 sec (Error code: 070)");

                    if ($user_result->num_rows > 0) {
                        $disable_query = "UPDATE users SET active=0 WHERE id = $user_id and active=1";

                        if (mysqli_query($connection, $disable_query)) {
                            array_push($errors, "Please contact administrator.");
                        } else {
                            array_push($errors, "User was disabled. Please contact administrator. (Error code: 079)");
                        }
                    }
                } else {
                    array_push($errors, "Please enter valid login details.<br/>$rem_attm attempts remaining");
                }
                $try_time = time();
                mysqli_query($connection, "INSERT INTO loginlogs(IpAddress,TryTime) VALUES('$ip_address','$try_time')");
            }
        }
    }
}

// Getting IP Address
function getIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipAddr = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
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
    <title>Login | Helios Library</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/signin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
</head>

<body style="background-image: linear-gradient(rgba(31, 55, 99, 0.45), rgba(31, 55, 99, 0.45)), url('assets/images/library.jpeg'); background-size: cover;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <main class="form-signin w-100 m-auto">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                <img class="mb-4" src="assets/images/logo.png" alt="Helios Logo" height="175">
                                <div class="form-floating">
                                    <input type="username" pattern="[A-Za-z0-9]+" class="form-control" id="floatingInput" placeholder="Username" name="username">
                                    <label for="floatingInput">Username</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                                    <label for="floatingPassword">Password</label>
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </div>
                                <?php include('errors.php'); ?>
                                <br />
                                <input class="w-100 btn btn-lg btn-primary" type="submit" name="login-user" value="Sign in" style="background-color: #FFA500; border: #FFA500;">
                                <div class="checkbox mb-3">
                                    <br />
                                    <label>
                                        Don't have an account? <a href="pages/index.php">Create one</a>
                                    </label>
                                </div>
                                <div class="checkbox mb-1">
                                    <label>
                                        <a href="pages/admin_register.php">Register as admin</a>
                                    </label>
                                </div>
                            </form>
                        </main>
                    </div>
                </div>
                <footer class="my-5 pt-5 text-center text-small" style="color: white">
                    <p class="mb-1">All Rights Reserved &copy; 2022 Helios Library</p>
                    <p class="mb-1">Created by KC Marie Arce, Antonio Caballes, Diego Cardoso de Moura</p>
                </footer>
            </div>

        </div>
    </div>
    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#floatingPassword");

        togglePassword.addEventListener("click", function() {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // toggle the icon
            this.classList.toggle("bi-eye");
        });
    </script>
</body>

</html>