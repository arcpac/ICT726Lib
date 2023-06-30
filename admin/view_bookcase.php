<?php
session_start();
include('config.php');
include('util.php');
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
$messages = array();
$errors = array();
$update_capacity = false;

// view
if (isset($_GET['view'])) {
    $id = $_GET['view'];
    $bookcase = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$id");
    $bookcase = mysqli_fetch_array($bookcase);
    $bookcase_id = $bookcase['id'];
    $bookcase_capacity = $bookcase['capacity'];

    // get books from bookcase
    $books = mysqli_query($connection, "SELECT * FROM books WHERE bookcase_id=$bookcase_id");
    $book_count = $books->num_rows;
}
//deactivate
if (isset($_POST['deactivate']) && $_SESSION['user']['user_type'] == 'admin') {
    $query = "UPDATE bookcase SET archive=1 WHERE id=$bookcase_id";
    $update_books = "UPDATE books SET bookcase_id= NULL, heap=1, shelf_no=NULL WHERE bookcase_id=$id";
    mysqli_query($connection, $update_books);

    $_SESSION['message'] = "Bookcase deactivated";
    array_push($messages, $_SESSION['message']);
    if (mysqli_query($connection, $query)) {
        $_SESSION['message'] = "Bookcase deactivated";
        array_push($messages, $_SESSION['message']);
    } else {
        $_SESSION['error_message'] = "Deactivate bookcase not saved. Error: " . $query . mysqli_error($connection);
        array_push($errors, $_SESSION['error_message']);
    }
    header('location: bookcases.php?view=' . $bookcase_id);
}

//restore 
if (isset($_POST['restore']) && $_SESSION['user']['user_type'] == 'admin') {
    $query = "UPDATE bookcase SET archive=0 WHERE id=$bookcase_id";
    $_SESSION['message'] = "Bookcase restored";
    array_push($messages, $_SESSION['message']);
    if (mysqli_query($connection, $query)) {
        $_SESSION['message'] = "Bookcase restored";
        array_push($messages, $_SESSION['message']);
    } else {
        $_SESSION['error_message'] = "Restoring bookcase not saved. Error: " . $query . mysqli_error($connection);
        array_push($errors, $_SESSION['error_message']);
    }
    header('location: bookcases.php');
}

//update capacity
if (isset($_GET['capacity'])) {
    $update_capacity = true;
}

if (isset($_POST['save-capacity'])) {
    $id = $_POST['id'];
    $capacity =  $_POST['capacity'];

    $query_set_cap = "UPDATE bookcase SET capacity = $capacity WHERE id=$id";
    mysqli_query($connection, $query_set_cap);
    header('location: bookcases.php');
}
?>
<?php include 'header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="text-orange"><b>Bookcase view </b></h2>
</div>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../admin/index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="../admin/bookcases.php">Manage bookcase</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bookcase view</li>
    </ol>
</nav>
<div class="col-sm-4 col-md-8 col-lg-8 order-md-last">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-primary">Total books</span>
        <span class="badge bg-primary rounded-pill"><?= $book_count ?></span>
    </h4>
    <?php include('messages.php'); ?>
    <?php include('errors.php'); ?>
    <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
                <h6 class="my-0">Bookcase owner</h6>
                <small class="text-muted"><?php echo ucfirst(get_user($bookcase['user_id'])) ?></small>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
                <h6 class="my-0">Bookcase name</h6>
                <small class="text-muted"><?= $bookcase['name']; ?></small>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
                <h6 class="my-0">Bookcase description</h6>
                <small class="text-muted"><?= $bookcase['description']; ?></small>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
                <h6 class="my-0">Capacity
                    <a href="view_bookcase.php?view=<?php echo $bookcase_id; ?>&capacity=1" class=""><i class=""></i> Edit</a>
                </h6>
                <small class="text-muted">Setting a lower capacity will not remove exceeding books. The capacity will only reflect when the owner's collection of books count is lesser or equal to the value you will enter.</small>

                <?php if ($update_capacity == true) : ?>
                    <form method="POST" action="view_bookcase.php?view=<?= $bookcase_id ?>">
                        <input type="hidden" name="id" value="<?php echo is_null($bookcase_id) ? '' : $bookcase_id ?>">
                        <div class="col-md-3">
                            <div class="input-group mb-1">
                                <input class="form-control form-control-sm" type="number" id="capacity" name="capacity" placeholder="Enter capacity" value="<?php echo is_null($bookcase_capacity) ? '' : $bookcase_capacity ?>" required>
                                <input class="btn btn-outline-secondary btn-sm" type="submit" name="save-capacity" value="Set capacity">
                            </div>
                        </div>
                    </form>
                <?php endif ?>
            </div>
            <span class="text-muted"><?php echo $bookcase['capacity']; ?></span>
        </li>
    </ul>

    <?php if ($bookcase['archive'] == 0) { ?>
        <form method="POST" action="view_bookcase.php?view=<?= $bookcase_id ?>" class="card p-2">
            <input type="hidden" class="form-control" name="archive" placeholder="">
            <input type="submit" name="deactivate" class="btn btn-outline-danger deactivate" value="Deactivate">
            <small class="text-muted text-center">
                Deactiving this bookcase will not save books inside it.
            </small>
        </form>
    <?php } else { ?>
        <form method="POST" action="view_bookcase.php?view=<?= $bookcase_id ?>" class="card p-2">
            <input type="hidden" class="form-control" name="active" placeholder="">
            <input type="submit" name="restore" class="btn btn-secondary restore" value="Restore">
        </form>
    <?php } ?>

</div>

<?php include 'footer.php'; ?>