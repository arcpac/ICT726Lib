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
    echo 'isset good';
    die('Connection unsuccessful' . mysqli_connect_error());
}

$user_id = $_SESSION['user']['id'];
$bookcases = "SELECT * FROM bookcase where user_id = $user_id and archive=0 ORDER BY name";

$messages = array();
$errors = array();

if (isset($_POST['deactivate'])) {
    $bookcase_id = $_POST['id'];
    $update_bookcase = "UPDATE bookcase SET archive=1 WHERE id=$bookcase_id";
    $update_books = "UPDATE books SET bookcase_id= NULL, heap=1, shelf_no=NULL WHERE bookcase_id=$bookcase_id";
    mysqli_query($connection, $update_books);

    $_SESSION['message'] = "Bookcase archived";
    // array_push($messages, $_SESSION['message']);
    if (mysqli_query($connection, $update_bookcase)) {
        $_SESSION['message'] = "Bookcase deleted";
        array_push($messages, $_SESSION['message']);
    } else {
        $_SESSION['error_message'] = "Deleting bookcase. Error: " . $update_bookcase . mysqli_error($connection);
        array_push($errors, $_SESSION['error_message']);
    }
    // header('location: index.php');
}
?>
<?php include('header.php'); ?>

<div id="myCarousel" class="carousel slide py-5" data-bs-ride="carousel">
    <!-- Carousel indicators -->
    <ol class="carousel-indicators">
        <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="3"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="4"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="5"></li>
    </ol>

    <!-- Wrapper for carousel items -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../assets/images/slider6.jpg" class="d-block w-100" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="../assets/images/slider1.jpg" class="d-block w-100" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="../assets/images/slider2.jpg" class="d-block w-100" alt="Slide 3">
        </div>
        <div class="carousel-item">
            <img src="../assets/images/slider3.jpg" class="d-block w-100" alt="Slide 4">
        </div>
        <div class="carousel-item">
            <img src="../assets/images/slider4.jpg" class="d-block w-100" alt="Slide 5">
        </div>
    </div>

    <!-- Carousel controls -->
    <a class="carousel-control-prev" href="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>
</div>
<main>
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">
                    <p>
                        <?php if (isset($_SESSION['username'])) : ?>
                            Welcome, <b><?php echo $_SESSION['username']; ?></b>!
                        <?php endif ?>
                    </p>
                </h1>
                <p class="lead text-muted">
                    Manage your books, shelves, and bookcases with Helios Library, an online library management system just at your fingertips.
                </p>
                <p>
                    <a href="add_bookcase.php" class="btn btn-primary my-2" style="background-color: #ff8e00 !important; border:#ff8e00 !important; ">Add bookcase</a>
                </p>
                <?php include('messages.php'); ?>
                <?php include('errors.php'); ?>
            </div>
        </div>
    </section>
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php $results = mysqli_query($connection, $bookcases); ?>
                <?php while ($row = mysqli_fetch_array($results)) { ?>
                    <?php $i = 1; ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="../assets/images/book-image.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p style="font-weight: bold"><?php echo $row['name']; ?></p>
                            </div>
                            <?php
                            $bookcase_id = $row['id'];
                            $book_results = mysqli_query($connection, "SELECT * FROM books WHERE bookcase_id=$bookcase_id");
                            ?>
                            <div class="card-body">
                                <small class="text-muted">
                                    Bookcase capacity: <?php echo $row['capacity']; ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    Bookcase shelf: <?php echo $row['no_of_shelf']; ?>
                                </small>
                                <?php if ($book_results->num_rows > 0) { ?>
                                    <a class="btn btn-light btn-sm" data-bs-toggle="collapse" href="#collapse<?php echo $row['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Books
                                    </a>
                                    <div class="collapse" id="collapse<?php echo $row['id']; ?>">
                                        <div class="card card-body">
                                            <ul>
                                                <?php while ($book = mysqli_fetch_array($book_results)) { ?>
                                                    <li>
                                                        <?php echo $book['title']; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>

                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="edit_bookcase.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a type="button" class="btn btn-sm btn-outline-secondary" href="view_bookcase.php?view=<?php echo $row['id'] ?>">View bookcase</a>
                                    </div>
                                    <small class="text-muted"><?php echo $book_results->num_rows; ?> book/s</small>
                                    <form method="POST" action="index.php" class="">
                                        <input type="hidden" class="form-control" name="id" value="<?php echo $row['id']; ?>" placeholder="">
                                        <div style="border: 1px solid #DDD;">
                                            <button id="delete_button" style="border: none;" type="submit" name="deactivate" class="btn btn-light btn-small text-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>




                        </div>
                    </div>
                    <?php $i++; ?>
                <?php } ?>
            </div>
        </div>
    </div>


</main>
<?php include('footer.php'); ?>
<script>
    $(document).ready(function() {
        $(".alert").fadeOut(3000);
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>