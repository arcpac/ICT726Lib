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
unset($_SESSION['message']);
unset($_SESSION['errors']);
$user_id = $_SESSION['user']['id'];
$books = mysqli_query($connection, "SELECT * FROM books WHERE heap = 1");
$bookcases = mysqli_query($connection, "SELECT * FROM bookcase WHERE user_id=$user_id and archive=0");
$errors = array();
include('util.php');

if (isset($_POST['assign-book'])) {
    $book_id = $_POST['book_id'];
    $bookcase_id = $_POST['bookcase_id'];
    $bookcase = get_bookcase($connection, $bookcase_id);
    if (empty($bookcase_id)) {
        array_push($errors, "Please select a bookcase");
    }
    if (check_bookcase_capacity($bookcase_id) == 1) {
        $bookcase_book_count = mysqli_query($connection, "SELECT * FROM books WHERE bookcase_id=$bookcase_id");
        $bookcase_book_count = $bookcase_book_count->num_rows;
        $next_slot = put_shelf($bookcase, $bookcase_book_count);
        if ($shelves < $bookcase['capacity']) {
            $book_id = $_POST['book_id'];
            $book = "UPDATE books SET bookcase_id=$bookcase_id, shelf_no=$next_slot, heap=0 WHERE id=$book_id;";

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
        header('location: heap.php');
    } else {
        $_SESSION['error_message'] = "You do not have enough space for this bookcase.";
        array_push($errors, $_SESSION['error_message']);
    }
}

?>

<?php include 'header.php'; ?>

<script>
    document.title = 'Books in Heap | Helios Library';
</script>

<main class="container py-5">
    <section class="py-5 text-center">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">
                    Books in Heap
                </h1>
                <p class="lead text-muted">
                    Discover books from various authors and genre. Add them directly to your bookcases!
                </p>
            </div>
        </div>
    </section>
    <?php if ($books->num_rows != 0) { ?>
        <div class="table-responsive">
            <table id="example" class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Book title</th>
                        <th scope="col">Author</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Assign bookcase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include('errors.php'); ?>
                    <?php $i = 1;
                    while ($book = mysqli_fetch_array($books)) { ?>
                        <tr>
                            <td><?php echo $i;
                                $i++; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo author_name($book['author_id']); ?></td>
                            <td><?php echo $book['isbn']; ?></td>
                            <td><?php echo genre_name($book['genre_id']); ?></td>
                            <td>
                                <form action="heap.php" class="col" method="POST">
                                    <input type="hidden" name="book_id" id="book" value="<?= $book['id'] ?>">
                                    <select id="<?= $book['id'] ?>" class="form-select form-select-sm bookcases" aria-label="Default select example" name="bookcase_id">
                                        <option value="" selected>Choose a bookcase</option>
                                        <?php while ($bookcase = mysqli_fetch_array($bookcases)) { ?>
                                            <option class="dropdown-item" value=<?= $bookcase['id'] ?>> <?= $bookcase['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <input id="assign-book-button<?= $book['id'] ?>" type="submit" name="assign-book" value="Assign to bookcase" class="btn btn-secondary btn-sm bookcases_select">
                                </form>
                            </td>
                        </tr>
                        <?php mysqli_data_seek($bookcases, 0); ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="mx-auto text-center">
            <img src="../assets/images/heap.png" class="w-50" alt="Books in Heap (empty)">
        </div>
    <?php } ?>
</main>
<?php include('footer.php'); ?>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $(".alert").fadeOut(3000);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>