<?php
session_start();
include('config.php');
if ($_SESSION['user']['user_type'] != 'admin') {
    $_SESSION['msg'] = "You must log in first";
    unset($_SESSION['user']);
    session_destroy();
    header('location: ../index.php');
}
$server_name = 'localhost';
$username = 'root';
$password = '';
$database_name = 'library';

$connection = mysqli_connect($server_name, $username, $password, $database_name);

if (!$connection) {
    echo 'isset good';
    die('Connection unsuccessful' . mysqli_connect_error());
}

$id = '';
$genre = '';
$update = false;
$messages = array();
$errors = array();

// SAVE BOOK
if (isset($_POST['save-genre'])) {
    $genre = mysqli_real_escape_string($connection, $_POST['genre']);
    $query = "INSERT INTO genre (genre) 
        VALUES ('$genre')";

    $check = mysqli_query($connection, "SELECT * FROM genre where genre='$genre'");
    $checkrows = mysqli_num_rows($check);
    if ($checkrows > 0) {
        array_push(
            $errors,
            "Duplicate is not allowed"
        );
    } else {
        if ($connection->query($query)) {
            array_push(
                $messages,
                $_SESSION['message'] = "Genre is added!"
            );
        } else {
            array_push(
                $errors,
                "Error in creating record. Please contact administrator."
                // "Error description: " . $connection->error
            );
        }
    }
}
?>
<?php
// UPDATE GENRE
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = mysqli_query($connection, "SELECT * FROM genre WHERE id=$id");

    if ($result->num_rows == 1) {
        $genre = mysqli_fetch_array($result);
        $genre = $genre['genre'];
    }
}

if (isset($_POST['update-genre'])) {
    $id = $_POST['id'];
    $genre = mysqli_real_escape_string($connection, $_POST['genre']);

    $query = "UPDATE genre SET 
    genre='$genre'
     WHERE id=$id";

    // mysqli_query($connection, $query);
    if ($connection->query($query)) {
        array_push(
            $messages,
            $_SESSION['message'] = "Genre updated!"
        );
    } else {
        array_push(
            $errors,
            "Error description updating record. Please contact administrator."
            // "Error description: " . $connection->error
        );
    }
}
?>

<?php
// DELETE BOOK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM genre WHERE id=$id");
    header('location: genre.php?d=1');
}
?>
<?php include 'header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="text-orange"><b>Manage genre</b></h2>
    <a id="create-btn" href="genre.php?" type="button" class="btn btn-outline-success">Add genre</a>
</div>
<?php include('messages.php'); ?>
<?php include('errors.php'); ?>
<?php include('util.php'); ?>
<?php $results = mysqli_query($connection, "SELECT * FROM genre"); ?>

<script>
    document.title = 'Genre Management | Helios Library';
</script>

<div class="table-responsive">
    <table id="example" class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Genre</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            while ($row = mysqli_fetch_array($results)) { ?>
                <tr>
                    <td><?php echo $i;
                        $i++; ?></td>
                    <td><?php echo $row['genre']; ?></td>
                    <td>
                        <a href="genre.php?edit=<?php echo $row['id']; ?>" class="edit_btn btn btn-secondary btn-sm" data-bs-toggle="ltooltip" title="Edit"><i class="fa fa-edit"></i></a>
                        <?php if (!genre_has_books($row['id']) > 0) { ?>
                            <a href="genre.php?delete=<?php echo $row['id']; ?>" class="del_btn btn btn-danger btn-sm" data-bs-toggle="rtooltip" title="Delete"><i class="fa fa-trash"></i></a>
                        <?php } ?>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<br /><br />

<form id="create-genre-form" class="genre-form needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" novalidate>
    <fieldset class="border rounded-3 row g-3 p-3">
        <?php if ($update == true) : ?>
            <!-- <h3 style="color: orange;">Update Book</h3> -->
            <legend class="float-none w-auto px-3">Update Genre</legend>
        <?php else : ?>
            <!-- <h3>Add Book</h3> -->
            <legend class="float-none w-auto px-3">Add Genre</legend>
        <?php endif ?>
        <input type="hidden" name="id" value="<?php echo is_null($id) ? '' : $id ?>">

        <div class="col-md-12">
            <label class="form-label" for="genre">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre" placeholder="Enter genre details" value="<?php echo is_null($genre) ? '' : $genre ?>" required>
            <div class="invalid-feedback">Please enter a genre title.</div>
        </div>

        <div class="col-md-4">
            <?php if ($update == true) : ?>
                <input type="submit" name="update-genre" value="Update genre" class="btn btn-warning">
            <?php else : ?>
                <input type="submit" value="Add genre" id="create-btn" name="save-genre" class="btn" style="background-color: #ff8e00 !important; border: #ff8e00 1px solid; color: white">
            <?php endif ?>
        </div>
        <br />
    </fieldset>
</form>


<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $(".alert").fadeOut(3000);
        $('[data-bs-toggle="rtooltip"]').tooltip({
            placement: 'right'
        });
        $('[data-bs-toggle="ltooltip"]').tooltip({
            placement: 'left'
        });
    });
</script>

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
</script>

<?php include 'footer.php'; ?>