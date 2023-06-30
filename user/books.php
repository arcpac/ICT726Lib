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

$user_id = $_SESSION['user']['id'];
$query = "SELECT books.*, bookcase.id as bookcase_id, bookcase.name as bookcase_name FROM `bookcase`
inner join books on books.bookcase_id = bookcase.id
where bookcase.user_id = $user_id";
$messages = array();
$errors = array();
require_once('../functions.php');
include('util.php');
// View
if (isset($_GET['view'])) {
  $bookcase_id = $_GET['id'];
  $bookcase = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$bookcase_id");
}
// DELETE (remove from user)
if (isset($_POST['delete'])) {
  $id = $_POST['book_id'];

  $update_book = "UPDATE books SET bookcase_id= NULL, heap=1 WHERE id=$id";

  if ($connection->query($update_book)) {
    array_push(
      $messages,
      $_SESSION['message'] = "Bookcase is created!"
    );
  } else {
    array_push(
      $errors,
      "Error in creating record. Please contact administrator."
      // "Error description: " . $connection->error
    );
  }
  $connection->close();
  header('location: books.php');
}

?>
<?php include('header.php'); ?>

<script>
  document.title = 'My Books | Helios Library';
</script>

<main>
  <div class="container py-5">

    <section class="py-5 text-center">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="fw-light">
              <?php if (isset($_SESSION['username'])) : ?>
                <?php echo ucfirst($_SESSION['username']); ?>'s Books
              <?php endif ?>
          </h1>
          <p class="lead text-muted">
                    Locate all your books here. Search by author, genre, ISBN, or bookcase.
                </p>
        </div>
        <?php include('messages.php'); ?>
        <?php include('errors.php'); ?>
      </div>
    </section>


    <?php $results = mysqli_query($connection, $query); ?>
    <?php if ($results->num_rows != 0) { ?>
      <div class="table-responsive">
        <table id="example" class="table table-hover">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Book title</th>
              <th scope="col">Author</th>
              <th scope="col">ISBN</th>
              <th scope="col">Genre</th>
              <th scope="col">Bookcase</th>
              <th scope="col">Price</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            while ($book = mysqli_fetch_array($results)) { ?>
              <tr>
                <td><?php echo $i;
                        $i++; ?></td>
                <td><?php echo $book['title']; ?></td>
                <td><?php echo author_name($book['author_id']); ?></td>
                <td><?php echo $book['isbn'] ?></td>
                <td><?php echo genre_name($book['genre_id']); ?></td>
                <td><?php echo $book['bookcase_name'] ?></td>
                <td><?php echo '$'.$book['price'] ?></td>
                <td>
                  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="submit" name="delete" class="btn btn-danger btn-sm" value="Delete">
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } else { ?>
      <div class="mx-auto text-center">
        <img src="../assets/images/no-books.png" class="w-50" alt="No Books Available">
      </div>
    <?php   } ?>

  </div>
</main>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
</script>
<?php include('footer.php'); ?>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>