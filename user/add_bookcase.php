<?php
session_start();
$connection = include('config.php');
if ($_SESSION['user']['user_type'] != 'user') {
    $_SESSION['msg'] = "You must log in first";
    unset($_SESSION['user']);
    session_destroy();
    header('location: ../index.php');
}
if (!$connection) {
    die('Connection unsuccessful' . mysqli_connect_error());
}

$name = '';
$description = '';
$archive = 0;
$_SESSION['message'] = '';
$username = $_SESSION['username'];
$messages = array();
$errors = array();
$user_id = get_user_id($connection, $username);

if (isset($_POST['add-bookcase'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $description = mysqli_real_escape_string($connection, trim($_POST['description']));

    // get current book count
    $bookcase_user = mysqli_query($connection, "SELECT count(1) FROM bookcase WHERE user_id=$user_id");
    $bookcase_user_count = mysqli_fetch_array($bookcase_user);
    $total = $bookcase_user_count[0];

    if (empty($name)) {
        array_push($errors, "Name is required");
    } else {
        $query = "INSERT INTO bookcase (name, description, capacity, user_id, archive, timestamp) VALUES ('$name', '$description', 6, '$user_id', $archive, NOW())";
        $check = mysqli_query($connection, "SELECT * FROM bookcase where name='$name' and user_id=$user_id and archive=0");
        $existing_bookcase = mysqli_query($connection, "SELECT * FROM bookcase where name='$name' and user_id=$user_id and archive=1");
        $checkrows = mysqli_num_rows($check);
        $check_archive = mysqli_num_rows($existing_bookcase);
        echo $check_archive;
        if ($checkrows > 0) {
            echo " check rows";
            array_push(
                $errors,
                "Duplicate is not allowed"
            );
        } elseif ($check_archive > 0) {
            echo " restore";
            $existing_bookcase = mysqli_fetch_array($existing_bookcase);
            $id = $existing_bookcase['id'];
            $restore_bc = "UPDATE bookcase SET archive=0 WHERE id=$id and archive=1";
            if (
                $restore_bc = mysqli_query($connection, $restore_bc)
            ) {
                array_push(
                    $messages,
                    $_SESSION['message'] = "Bookcase is restored!"
                );
            } else {
                array_push(
                    $errors,
                    // "Error in creating record. Please contact administrator."
                    "Error description: " . $connection->error
                );
            }
        } else {
            if ($connection->query($query)) {
                echo " create";
                array_push(
                    $messages,
                    $_SESSION['message'] = "Bookcase is created!"
                );
            } else {
                array_push(
                    $errors,
                    // "Error in creating record. Please contact administrator."
                    "Error description: " . $connection->error
                );
            }
        }
        header('location: index.php');
    }
}


function get_user_id($connection, $username)
{
    $get_user = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($connection, $get_user);
    $user = mysqli_fetch_assoc($result);
    return $user['id'];
}

?>
<?php include('header.php'); ?>
<?php include('util.php'); ?>

<script>
    document.title = 'Add Bookcase | Helios Library';
</script>

<main>
    <div class="container py-5">

        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/bookcase.png" alt="Bookcase icon" width="72" height="57">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item text-center"><a href="../user/index.php">My bookcases</a></li>
                        <li class="breadcrumb-item active text-center" aria-current="page">Add bookcase</li>
                    </ol>
                </nav>
            <h2>
                <?php if (isset($_SESSION['username'])) : ?>
                    <?php echo ucfirst($_SESSION['username']); ?>'s New Bookcase
                <?php endif ?>
            </h2>
            <p class="lead">Add your new bookcase and store more books!</p>
        </div>

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span style="color: #ff8e00">Your bookcases</span>
                    <!-- <span class="badge bg-primary rounded-pill">3</span> -->
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-secondary">
                            <h6 class="my-0">Total books you have:</h6>
                            <span class="text-success"><?php echo book_count($user_id) ?></span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-secondary">
                            <h6 class="my-0">Bookcases you have:</h6>
                            <span class="text-success"><?php echo bookcase_count($user_id) ?></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-7 col-lg-8">
                <?php include('errors.php'); ?>
                <h4 class="mb-3">Add bookcase</h4>
                <form class="needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <input type="hidden" name="user_id" value="<?php echo is_null($id) ? '' : $id ?>">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="bookcase_name" class="form-label">Bookcase name</label>
                            <input type="text" class="form-control" id="bookcase_name" placeholder="Bookcase name" value="<?php echo $name; ?>" name="name">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Decribe this bookcase</label>
                            <textarea class="form-control" id="description" name="description" rows="3" maxlength="100"><?php echo $description ?></textarea>
                        </div>
                    </div>
                    <hr class="my-4">
                    <input class="w-100 btn btn-lg text-white" style="background-color: #ff8e00" type="submit" value="Add bookcase" name="add-bookcase">
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
<!-- <script src="../assets/dist/js/bootstrap.bundle.min.js"></script> -->

<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>