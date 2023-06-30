<?php
session_start();
if ($_SESSION['user']['user_type'] != 'user') {
    $_SESSION['msg'] = "You must log in first";
    unset($_SESSION['user']);
    session_destroy();
    header('location: ../index.php');
}

$server_name = 'localhost';
$s_username = 'root';
$s_password = '';
$database_name = 'library';

$connection = mysqli_connect($server_name, $s_username, $s_password, $database_name);

if (!$connection) {
    echo 'isset good';
    die('Connection unsuccessful' . mysqli_connect_error());
}

unset($_SESSION['message']);
unset($_SESSION['errors']);
$name = '';
$description = '';
// $capacity = '';
// $no_of_shelf = '';
$active = 1;
$errors = array();

// UPDATE BOOKCASE
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$id");

    if ($result->num_rows == 1) {
        $bookcase = mysqli_fetch_array($result);
        $id = $bookcase['id'];
        $name = $bookcase['name'];
        $capacity = $bookcase['capacity'];
        $no_of_shelf = $bookcase['no_of_shelf'];
        $description =  $bookcase['description'];
    }
}

// update
if (isset($_POST['update-bookcase'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $description =  mysqli_real_escape_string($connection, $_POST['description']);
    // $active =  $_POST['active'];
    $timestamp = date("Y-m-d H:i:s");

    if (empty($name)) {
        array_push($errors, "Bookcase name is required");
    }

    $check = mysqli_query($connection, "SELECT * FROM bookcase where name='$$name'");
    $checkrows = mysqli_num_rows($check);
    if ($checkrows > 0) {
        array_push(
            $errors,
            "Duplicate is not allowed"
        );
    } else {
        if (mysqli_query($connection, "UPDATE bookcase SET 
        name='$name',
        description='$description',
        timestamp='$timestamp'
        WHERE id=$id")) {
            header('location: index.php');
        } else {
            array_push(
                $errors,
                // "Error description updating record. Please contact administrator."
                "Error description: " . $connection->error
            );
        }
    }
}
// delete
if (isset($_POST['delete'])) {
    $id = $_POST['bookcase_id'];

    $update_books = "UPDATE books SET bookcase_id= NULL, heap=1 WHERE bookcase_id=$id";
    $update_bookcase = "UPDATE bookcase SET archive = 1 WHERE id=$id";

    mysqli_query($connection, $update_books);
    mysqli_query($connection, $update_bookcase);
    header('location: index.php');
}
?>

<?php
function get_user_id($connection, $username)
{
    $get_user = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($connection, $get_user);
    $user = mysqli_fetch_assoc($result);
    return $user['id'];
}

?>

<?php include('header.php'); ?>

<script>
    document.title = 'Edit Bookcase | Helios Library';
</script>

<main>
    <div class="container py-5">

        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/bookcase.png" alt="Bookcsase logo" width="72" height="57">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item text-center"><a href="../user/index.php">My bookcases</a></li>
                    <li class="breadcrumb-item active text-center" aria-current="page">Edit bookcase</li>
                </ol>
            </nav>
            <h2><?php echo $name ?> Bookcase</h2>
            <p class="lead">Customise your bookcase by editing its name or description. Have fun!</p>

        </div>

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span style="color: #ff8e00">Bookcase details</span>
                    <!-- <span class="badge bg-primary rounded-pill">3</span> -->
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-secondary">
                            <h6 class="my-0">Bookcase Name</h6>
                            <span class="text-secondary"><?php echo $name ?></span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-secondary">
                            <h6 class="my-0">Capacity</h6>
                            <small>No. of Shelves: <?php echo $bookcase['no_of_shelf']; ?></small>
                        </div>
                        <span class="text-secondary"><?php echo $bookcase['capacity']; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-secondary">
                            <h6 class="my-0">Description</h6>
                            <small><?php echo $bookcase['description']; ?></small>
                        </div>
                    </li>
                </ul>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <input type="hidden" name="bookcase_id" value="<?php echo $id; ?>">
                    <input type="submit" name="delete" class="btn btn-danger btn-sm" value="Delete bookcase">
                </form>
            </div>
            <div class="col-md-7 col-lg-8">
                <?php include('errors.php'); ?>
                <h4 class="mb-3">Edit bookcase</h4>
                <form class="needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="bookcase_name" class="form-label">Bookcase name</label>
                            <input type="text" class="form-control" id="bookcase_name" placeholder="Bookcase name" value="<?php echo $name; ?>" name="name">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Decribe this bookcase</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $description ?></textarea>
                        </div>
                    </div>
                    <hr class="my-4">
                    <button class="w-100 btn btn-lg text-white" style="background-color: #ff8e00" type="submit" name="update-bookcase">Update bookcase</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>


<?php if (isset($_GET['d'])) : ?>
    <div class="flash-data" data-flashdata="<?= $_GET['d']; ?>"></div>
<?php endif ?>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>
<!-- <script src="form-validation.js"></script> -->
<script>
    $('#test_btn').on('click', function() {
        Swal.fire(
            'Good job!',
            'You clicked the button!',
            'success'
        )
    })
</script>
<script>
    $(document).ready(function() {
        $(".alert").fadeOut(3000);
    });
    // function save() {
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>

</html>