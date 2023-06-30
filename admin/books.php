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
$title = '';
$author = '';
$genre = '';
$isbn = '';
$price =  '';
$update = false;
$heap = '';
$messages = array();
$errors = array();
$genre_list = "SELECT id, genre FROM genre";
$genre_list = mysqli_query($connection, $genre_list);

$author_list = "SELECT * FROM author";
$author_list = mysqli_query($connection, $author_list);
// $connection->close();
// SAVE BOOK
if (isset($_POST['save-book'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $author =  $_POST['author'];
    $genre =  $_POST['genre'];
    $isbn =  $_POST['isbn'];
    $price =  $_POST['price'];
    $heap =  1;
    $timestamp = date("Y-m-d H:i:s");
    $query = "INSERT INTO books (title, author_id, genre_id, isbn, price, heap, timestamp) 
        VALUES ('$title', '$author', '$genre', '$isbn', '$price', '$heap', NOW())";

    $check = mysqli_query($connection, "SELECT * FROM books where isbn='$isbn'");
    $checkrows = mysqli_num_rows($check);
    if ($checkrows > 0) {
        array_push(
            $errors,
            "Duplicate is not allowed. Please check the ISBN."
        );
    } else {
        if ($connection->query($query)) {
            array_push(
                $messages,
                $_SESSION['message'] = "Book is created!"
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
// UPDATE BOOK
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = mysqli_query($connection, "SELECT * FROM books WHERE id=$id");

    if ($result->num_rows == 1) {
        $book = mysqli_fetch_array($result);
        $title = $book['title'];
        $isbn =  $book['isbn'];
        $price =  $book['price'];
        $heap =  $book['heap'];
    }
}

if (isset($_POST['update-book'])) {
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $author =  $_POST['author'];
    $genre =  $_POST['genre'];
    $isbn =  $_POST['isbn'];
    $price =  $_POST['price'];
    $heap =  $_POST['heap'];
    $timestamp = date("Y-m-d H:i:s");

    $query = "UPDATE books SET 
    title='$title',
    author_id='$author',
    genre_id='$genre',
    isbn='$isbn',
    price='$price',
    heap='$heap',
    timestamp='$timestamp'
     WHERE id=$id";

    // mysqli_query($connection, $query);
    if ($connection->query($query)) {
        array_push(
            $messages,
            $_SESSION['message'] = "Book updated!"
        );
    } else {
        array_push(
            $errors,
            // "Error description updating record. Please contact administrator."
            "Error description: " . $connection->error
        );
    }
}
?>

<?php
// DELETE BOOK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM books WHERE id=$id");
    header('location: books.php?d=1');
}
?>
<?php include 'header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="text-orange"><b>Manage books</b></h2>
    <a id="create-btn" href="books.php?" type="button" class="btn btn-outline-success">Add book</a>
</div>
<?php include('messages.php'); ?>
<?php include('errors.php'); ?>
<?php include('util.php'); ?>
<?php $results = mysqli_query($connection, "SELECT * FROM books"); ?>

<script>
    document.title = 'Book Management | Helios Library';
</script>

<div class="table-responsive">
    <table id="example" class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Book Title</th>
                <th scope="col">Author</th>
                <th scope="col">Genre</th>
                <th scope="col">ISBN</th>
                <th scope="col">Price</th>
                <th scope="col">Book status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            while ($row = mysqli_fetch_array($results)) { ?>
                <tr>
                    <td><?php echo $i;
                        $i++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo author_name($row['author_id']); ?></td>
                    <td><?php echo genre_name($row['genre_id']); ?></td>
                    <td><?php echo $row['isbn']; ?></td>
                    <td><?php echo "$".$row['price']; ?></td>
                    <td>
                        <?php if (!$row['heap']) { ?>
                            <span class="badge text-bg-secondary">Unavailable</span>
                        <?php } else { ?>
                            <span class="badge text-bg-warning">In a heap</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="books.php?edit=<?php echo $row['id']; ?>" class="edit_btn btn btn-secondary btn-sm" data-bs-toggle="ltooltip" title="Edit"><i class="fa fa-edit"></i></a>
                        <a href="books.php?delete=<?php echo $row['id']; ?>" class="del_btn btn btn-danger btn-sm" data-bs-toggle="rtooltip" title="Delete"><i class="fa fa-trash"></i></a>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<br /><br />

<form id="create-book-form" class="book-form needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" novalidate>
    <fieldset class="border rounded-3 row g-3 p-3">
        <?php if ($update == true) : ?>
            <!-- <h3 style="color: orange;">Update Book</h3> -->
            <legend class="float-none w-auto px-3">Update Book</legend>
        <?php else : ?>
            <!-- <h3>Add Book</h3> -->
            <legend class="float-none w-auto px-3">Add Book</legend>
        <?php endif ?>
        <input type="hidden" name="id" value="<?php echo is_null($id) ? '' : $id ?>">

        <div class="col-md-4">
            <label class="form-label" for="title">Book Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter book title" value="<?php echo is_null($title) ? '' : $title ?>" required>
            <div class="invalid-feedback">Please enter the book title.</div>
        </div>

        <div class="col-md-4">
            <label for="author">Choose a author:</label>
            <select class="form-select selectpicker" name="author" id="author_list" data-live-search="true">
                <?php while ($author = mysqli_fetch_array($author_list)) { ?>
                    <option value="<?= $author['id'] ?>"><?= author_name($author['id']) ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="genre">Choose a genre:</label>
            <select class="form-select" name="genre" id="genre">
                <?php while ($genre = mysqli_fetch_array($genre_list)) { ?>
                    <option value="<?= $genre['id'] ?>"><?= $genre['genre'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label" for="isbn">ISBN</label>
            <input class="form-control" type="text" maxlength="13" pattern="^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$" id="isbn" name="isbn" placeholder="Enter book ISBN" value="<?php echo is_null($isbn) ? '' : $isbn ?>" required>
            <small class="form-text text-muted">Only certain combinations of 10 or 13 digits are valid ISBN numbers.</small>
            <div class="invalid-feedback">Please enter the book ISBN.</div>
        </div>

        <div class="col-md-4">
            <label class="form-label" for="price">Price</label>
            <input class="form-control" type="number" min="0" pattern="^-?[0-9]+(.[0-9]{1,2})?$" id="price" name="price" placeholder="Enter book price" value="<?php echo is_null($price) ? '' : $price ?>" required>
            <div class="invalid-feedback">Please enter the book price.</div>
        </div>

        <div class="col-md-4">
            <label class="form-label" for="heap">Place book in heap?</label>
            <select class="form-select" name="heap" id="heap" required>
                <option value="0">No</option>
                <option value="1" selected>Yes</option>
            </select>
        </div>

        <div class="col-md-4">
            <?php if ($update == true) : ?>
                <input type="submit" name="update-book" value="Update Book" class="btn btn-warning">
            <?php else : ?>
                <input type="submit" value="Add book" id="create-btn" name="save-book" class="btn" style="background-color: #ff8e00 !important; border: #ff8e00 1px solid; color: white">
            <?php endif ?>
        </div>
        <br />
    </fieldset>
</form>

<script src="../assets/js/dselect.js"></script>
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

<script>
    dselect(document.querySelector('#author_list'), {
        search: true
    })
</script>

<?php include 'footer.php'; ?>