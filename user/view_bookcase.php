<?php
session_start();
if ($_SESSION['user']['user_type'] != 'user') {
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


// variables
unset($_SESSION['message']);
unset($_SESSION['errors']);
$user_id = $_SESSION['user']['id'];
$add = false;
$messages = array();
$errors = array();
// FUNCTIONS
include('util.php');
if (isset($_GET['view'])) {
    $id = $_GET['view'];
    $bookcase = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$id");
    $bookcase = mysqli_fetch_array($bookcase);
    $bookcase_id = $bookcase['id'];
    // get books from bookcase
    $books = mysqli_query($connection, "SELECT * FROM books WHERE bookcase_id=$bookcase_id ORDER BY shelf_no");
    $available_books = mysqli_query($connection, "SELECT * FROM books WHERE heap=1 ORDER BY title");
}

if (isset($_POST['add-book'])) {
    $bookcase_id = $_POST['bookcase_id'];
    $bookcase = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$bookcase_id");
    $bookcase = mysqli_fetch_array($bookcase);
    $bookcase_book_count = mysqli_query($connection, "SELECT * FROM books WHERE bookcase_id=$bookcase_id");
    $bookcase_book_count = mysqli_num_rows($bookcase_book_count);
    $next_slot = put_shelf($bookcase, $bookcase_book_count);
    if (check_bookcase_capacity($bookcase_id) == 1) {
        $book_id = $_POST['book_id'];
        $book = "UPDATE books SET bookcase_id=$bookcase_id, shelf_no=$next_slot, heap=0 WHERE id=$book_id";
        if (mysqli_query($connection, $book)) {
            echo "Good";
        } else {
            $error = "Address not saved. Error: " . $book . mysqli_error($connection);
            echo $error;
        }
        $_SESSION['message'] = "Book added to " . $bookcase['name'];
        header('location: view_bookcase.php?view=' . $bookcase_id);
    } else {
        $_SESSION['error_message'] = "You do not have enough space for this bookcase.";
        array_push($errors, $_SESSION['error_message']);
    }
}

if (isset($_POST['remove'])) {
    $book_id = $_POST['book_id'];
    $bookcase_id = $_GET['view'];
    $book = "UPDATE books SET bookcase_id=NULL, shelf_no=NULL, heap=1 WHERE id=$book_id";

    $_SESSION['message'] = "Book removed";
    if (mysqli_query($connection, $book)) {
        $_SESSION['message'] = "Book removed";
        array_push($messages, $_SESSION['message']);
    } else {
        $_SESSION['error_message'] = "Deactivate bookcase not saved. Error: " . $book . mysqli_error($connection);
        array_push($errors, $_SESSION['error_message']);
    }
    header('location: view_bookcase.php?view=' . $bookcase_id);
}
?>
<?php include('header.php'); ?>

<script>
    document.title = 'View Bookcase | Helios Library';
</script>

<main class="contaienr py-5">
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/bookcase.png" alt="Bookcase icon" width="72" height="57">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item text-center"><a href="../user/index.php">My bookcases</a></li>
                        <li class="breadcrumb-item active text-center" aria-current="page">View bookcase</li>
                    </ol>
                </nav>        
            <h2><?php echo $bookcase['name'] ?></h2>
                
                
                <p class="lead text-muted"> Capacity: <?php echo $bookcase['capacity'] ?> </p>
                <?php include('messages.php'); ?>
                <?php include('errors.php'); ?>
                <p class="lead text-muted">
                    <?php echo $bookcase['description']; ?>
                </p>
            </div>
            <div class="row justify-content-center">
                <div class="col-2"></div>
                <div class="col-6">
                    <?php if ($add = true) { ?>
                        <div class="dropdown">
                            <form action="view_bookcase.php?view=<?= $id ?>" method="POST">
                                <input type="hidden" name="bookcase_id" value="<?= $bookcase_id ?>">
                                <div class="mb-3">
                                    <select id="select_book" class="form-select form-select-sm" aria-label="Default select example" name="book_id">
                                        <option selected>Choose a book</option>
                                        <?php while ($available_book = mysqli_fetch_array($available_books)) { ?>
                                            <option class="dropdown-item" value=<?= $available_book['id'] ?>> <?= $available_book['title'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input id="add_book_in_bc" type="submit" name="add-book" class="btn text-white" style="background-color: #ff8e00" value="Add book">
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
    </section>
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">

                <?php while ($book = mysqli_fetch_array($books)) { ?>
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal text-truncate"><?= $book['title'] ?></h4>
                            </div>
                            <div class="card-body">
                                <!-- <h1 class="card-title pricing-card-title">d</h1> -->
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li>Author: <?php echo author_name($book['author_id']); ?></li>
                                    <li>Genre: <?php echo genre_name($book['genre_id']); ?></li>
                                    <li>ISBN: <?= $book['isbn'] ?></li>
                                    <li><b>Shelf No.: <?= $book['shelf_no'] ?></b></li>
                                </ul>
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <!-- <button type="button" class="btn btn-outline-primary">Read</button> -->
                                    <form action="view_bookcase.php?view=<?php echo $id ?>" method="post">
                                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                        <button type="submit" name="remove" class="btn btn-outline-danger">Remove book</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>

</html>