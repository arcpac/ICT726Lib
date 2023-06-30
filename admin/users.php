<?php
session_start();
include('config.php');
if ($_SESSION['user']['user_type'] != 'admin') {
    $_SESSION['msg'] = "You must log in first";
    unset($_SESSION['user']);
    session_destroy();
    header('location: ../index.php');
}

if (!$connection) {
    echo 'isset good';
    die('Connection unsuccessful' . mysqli_connect_error());
}

unset($_SESSION['message']);
unset($_SESSION['errors']);
$id = '';
$username = '';
$email = '';
$user_type = '';
$active = false;
$password = '';
$messages = array();
$errors = array();

if(isset($_POST['disable'])){
    $user_id = $_POST['user_id'];
    $disable_query = "UPDATE users SET active=0 WHERE id = $user_id";
    if(mysqli_query($connection, $disable_query)) {
        array_push($messages, "User has been disabled.");
    } else {
        array_push($errors, "Unable to to action.");
    }
}
if (isset($_POST['approve_admin'])) {
    $user_id = $_POST['user_id'];
    $approve_query = "UPDATE users SET user_type='admin', active=1 WHERE id = $user_id";
    if (mysqli_query($connection, $approve_query)) {
        array_push($messages, "Admin has been approved.");
    } else {
        array_push($errors, "Unable to to action.");
    }
}
if(isset($_POST['reset_pass'])){
    $user_id = $_POST['user_id'];
    $user = "SELECT * FROM users WHERE id=$user_id";
    $user = mysqli_query($connection, $user);
    $user = mysqli_fetch_array($user);

    // $key = rand(10,1000).$user_id;
    $password_key = '321';
    $password_key = md5($password_key);
    $reset_query = "UPDATE users SET active=1, password='$password_key' WHERE id = $user_id";
    if (mysqli_query($connection, $reset_query)) {
        array_push($messages, "User account has been reactivated.");
    } else {
        array_push($errors, "");
        echo "Register Error: " . mysqli_error($connection);
    }
}
?>

<!-- DISPLAY DATA -->
<?php include 'header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="text-orange"><b>Manage users</b></h2>
</div>
<?php include('messages.php'); ?>
<?php include('errors.php'); ?>
<?php if (isset($_SESSION['message'])) : ?>
    <div class="msg" style="color: green;">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        ?>
    </div>
<?php endif ?>

<?php if (isset($_SESSION['error_message'])) : ?>
    <div class="msg" style="color: red;">
        <?php
        echo $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        ?>
    </div>
<?php endif ?>

<?php $results = mysqli_query($connection, "SELECT * FROM users WHERE user_type='user' or user_type='approval'"); ?>

<script>
    document.title = 'User Management | Helios Library';
</script>

<div class="table-responsive">
    <table id="example" class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            while ($row = mysqli_fetch_array($results)) { ?>
                <tr>
                    <td><?php echo $i;
                        $i++; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <?php if ($row['active']) { ?>
                            <span class="badge text-bg-success">Active</span>
                        <?php } else { ?>
                            <span class="badge text-bg-danger">Disabled</span>
                        <?php } ?>
                    </td>
                    <td>
                        <form action="users.php" method="POST">
                            <input type="hidden" value="<?php echo $row['id']; ?>" name="user_id">
                            <?php if ($row['active'] == 0 && $row['user_type'] == 'user') { ?>
                            <button name="reset_pass" class="view btn btn-secondary btn-sm" type="submit"><i class="fa fa-eye"></i> Reset Password </button>
                            <?php } else if ($row['user_type'] == 'approval') { ?>
                            <button name="approve_admin" class="view btn btn-info btn-sm" type="submit"><i class="fa fa-check-square"></i> Approve admin</button>
                            <?php } else { ?>
                            <button name="disable" class="view btn btn-danger btn-sm" type="submit"><i class="fa fa-eye"></i> Disable User</button>
                            <?php }?>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });

    $(document).ready(function() {
        $(".alert").fadeOut(3000);
    });
</script>

