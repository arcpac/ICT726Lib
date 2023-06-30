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

$server_name = 'localhost';
$username = 'root';
$password = '';
$database_name = 'library';

$connection = mysqli_connect($server_name, $username, $password, $database_name);

if (!$connection) {
    die('Connection unsuccessful' . mysqli_connect_error());
}

unset($_SESSION['message']);
unset($_SESSION['errors']);
$id = '';
$title = '';
$author = '';
$genre = '';
$isbn = '';
$price =  '';
$bookcase = '';
$update = false;
$heap = '';

// SAVE BOOK
if (isset($_POST['save-book'])) {
    $title = $_POST['title'];
    $author =  $_POST['author'];
    $genre =  $_POST['genre'];
    $isbn =  $_POST['isbn'];
    $price =  $_POST['price'];
    $bookcase =  $_POST['bookcase'];
    $heap =  $_POST['heap'];
    $timestamp = date("Y-m-d H:i:s");
    $query = "INSERT INTO books (title, author, genre, isbn, price, bookcase, heap, timestamp) 
        VALUES ('$title', '$author', '$genre', '$isbn', '$price', '$bookcase', '$heap', NOW())";

    if (mysqli_query($connection, $query)) {
        $_SESSION['message'] = "Book saved";
    } else {
        $_SESSION['error_message'] = "Error: " . $query . mysqli_error($connection);
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
        $author =  $book['author'];
        $genre =  $book['genre'];
        $isbn =  $book['isbn'];
        $price =  $book['price'];
        $bookcase =  $book['bookcase'];
        $heap =  $book['heap'];
    }
}

if (isset($_POST['update-book'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author =  $_POST['author'];
    $genre =  $_POST['genre'];
    $isbn =  $_POST['isbn'];
    $price =  $_POST['price'];
    $bookcase =  $_POST['bookcase'];
    $heap =  $_POST['heap'];
    $timestamp = date("Y-m-d H:i:s");

    mysqli_query($connection, "UPDATE books SET 
    title='$title',
    author='$author',
    genre='$genre',
    isbn='$isbn',
    price='$price',
    bookcase='$bookcase',
    heap='$heap',
    timestamp='$timestamp'
     WHERE id=$id");

    $_SESSION['message'] = "Book updated!";
    header('location: books.php');
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

<!-- DISPLAY DATA -->
<?php include 'header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="text-orange"><b>Manage bookcase</b></h2>
</div>

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

<?php $results = mysqli_query($connection, "SELECT * FROM bookcase"); ?>

<script>
    document.title = 'Bookcase Management | Helios Library';
</script>

<div class="table-responsive">
    <table id="example" class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Bookcase name</th>
                <th scope="col">Description</th>
                <th scope="col">Capacity</th>
                <th scope="col">Owner</th>
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
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['capacity']; ?></td>
                    <td><?php echo get_user($row['user_id']) ?></td>
                    <td>
                        <?php if ($row['archive']) { ?>
                            <span class="badge text-bg-secondary">Archived</span>
                        <?php } else { ?>
                            <span class="badge text-bg-success">Active</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="view_bookcase.php?view=<?php echo $row['id']; ?>" class="view btn btn-info btn-sm" data-bs-toggle="rtooltip" title="View"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('[data-bs-toggle="rtooltip"]').tooltip({
            placement: 'right'
        });
    });
</script>

<?php include 'footer.php'; ?>