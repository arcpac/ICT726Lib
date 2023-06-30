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
$firstname = '';
$lastname = '';
$update = false;
$messages = array();
$errors = array();

include('util.php');
// SAVE AUTHOR
if (isset($_POST['save-author'])) {
    $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($connection,$_POST['lastname']);
    $query = "INSERT INTO author (firstname, lastname) 
        VALUES ('$firstname', '$lastname')";

    $check = mysqli_query($connection, "SELECT * FROM author where firstname='$firstname' and lastname='$lastname'");
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
                $_SESSION['message'] = "Author is added!"
            );
        } else {
            array_push(
                $errors,
                // "Error in creating record. Please contact administrator."
                "Error description: " . $connection->error
            );
        }
    }
}
?>
<?php
// UPDATE AUTHOR
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = mysqli_query($connection, "SELECT * FROM author WHERE id=$id");

    if ($result->num_rows == 1) {
        $author = mysqli_fetch_array($result);
        $firstname = $author['firstname'];
        $lastname = $author['lastname'];
    }
}

if (isset($_POST['update-author'])) {
    $id = $_POST['id'];
    $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);

    $query = "UPDATE author SET 
    firstname='$firstname',
    lastname='$lastname'
     WHERE id=$id";

    // mysqli_query($connection, $query);
    if ($connection->query($query)) {
        array_push(
            $messages,
            $_SESSION['message'] = "Author details updated!"
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
// DELETE AUTHOR
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM author WHERE id=$id");
    header('location: authors.php?d=1');
}
?>
<?php include 'header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="text-orange"><b>Manage authors</b></h2>
    <a id="create-btn" href="authors.php?" type="button" class="btn btn-outline-success">Add author</a>
</div>
<?php include('messages.php'); ?>
<?php include('errors.php'); ?>
<?php $results = mysqli_query($connection, "SELECT * FROM author"); ?>

<script>
    document.title = 'Author Management | Helios Library';
</script>

<div class="table-responsive">
    <table id="example" class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            while ($row = mysqli_fetch_array($results)) { ?>
                <tr>
                    <td><?php echo $i;
                        $i++; ?></td>
                    <td><?php echo $row['firstname']; ?></td>
                    <td><?php echo $row['lastname']; ?></td>
                    <td>
                        <a href="authors.php?edit=<?php echo $row['id']; ?>" class="edit_btn btn btn-secondary btn-sm" data-bs-toggle="ltooltip" title="Edit"><i class="fa fa-edit"></i></a>
                        <?php if (!author_has_books($row['id'])>0) { ?>
                            <a href="authors.php?delete=<?php echo $row['id']; ?>" class="del_btn btn btn-danger btn-sm" data-bs-toggle="rtooltip" title="Delete"><i class="fa fa-trash"></i></a>
                        <?php } ?>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<br /><br />

<form id="create-author-form" class="author-form needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" novalidate>
    <fieldset class="border rounded-3 row g-3 p-3">
        <?php if ($update == true) : ?>
            <!-- <h3 style="color: orange;">Update Book</h3> -->
            <legend class="float-none w-auto px-3">Update Author</legend>
        <?php else : ?>
            <!-- <h3>Add Book</h3> -->
            <legend class="float-none w-auto px-3">Add Author</legend>
        <?php endif ?>
        <input type="hidden" name="id" value="<?php echo is_null($id) ? '' : $id ?>">

        <div class="col-md-6">
            <label class="form-label" for="firstname">First Name</label>
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" value="<?php echo is_null($firstname) ? '' : $firstname ?>" required>
            <div class="invalid-feedback">Please enter author's first name.</div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="lastname">Last Name</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter last name" value="<?php echo is_null($lastname) ? '' : $lastname ?>">
        </div>

        <div class="col-md-4">
            <?php if ($update == true) : ?>
                <input type="submit" name="update-author" value="Update author" class="btn btn-warning">
            <?php else : ?>
                <input type="submit" value="Add author" id="create-btn" name="save-author" class="btn" style="background-color: #ff8e00 !important; border: #ff8e00 1px solid; color: white">
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