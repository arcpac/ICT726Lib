<?php
session_start();
include('config.php');
if ($_SESSION['user']['user_type'] != 'admin') {
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
?>
<!-- GET BOOKS -->
<?php $results = mysqli_query($connection, "SELECT * FROM books"); ?>
<?php include('header.php'); ?>
<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
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

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2 text-orange"><b>Dashboard</b></h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2 text-secondary">
      <?php if (isset($_SESSION['username'])) : ?>
        <p>Current user: <?php echo $_SESSION['username']; ?></p>
      <?php endif ?>
    </div>
  </div>
</div>

<h5>
  <!-- notification message -->
  <?php if (isset($_SESSION['success'])) : ?>
    <div class="error success">
      <h3>
        <?php
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
      </h3>
    </div>
  <?php endif ?>

  Hello <b><?php echo $_SESSION['user']['username']; ?></b>, welcome back
</h5>

<div class="container">
  <div class="row">

    <div class="col-lg-4 col-md-6" style="margin-top: 20px">
      <div class="card shadow-sm p-2 mb-5 bg-white rounded">
        <a href="../admin/users.php" class="dash">
          <div class="card-body">
            <div class="row">
              <div class="col-3">
                <i class="fa fa-ban fa-4x" style="color: #ff8e00"></i>
              </div>
              <?php
              $sql = "SELECT id from users WHERE active=0 and user_type='user' ";
              $result = $connection->query($sql);
              $result = $result->num_rows;
              ?>
              <div class="col-9 text-right">
                <h2><?php echo $result ?></h2>
                <h5 class="text-secondary">Inactive Users</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-4 col-md-6" style="margin-top: 20px">
      <div class="card shadow-sm p-2 mb-5 bg-white rounded">
        <a href="../admin/users.php" class="dash">
          <div class="card-body ">
            <div class="row">
              <div class="col-3">
                <i class="fa fa-users fa-4x" style="color: #ff8e00"></i>
              </div>
              <?php
              $sql = "SELECT id from users WHERE user_type='user'";
              $users = $connection->query($sql);
              $users = $users->num_rows;
              ?>
              <div class="col-9 text-right">
                <h2><?php echo htmlentities($users); ?></h2>
                <h5 class="text-secondary">Registered Users</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-4 col-md-6" style="margin-top: 20px">
      <div class="card shadow-sm p-2 mb-5 bg-white rounded">
        <a href="../admin/authors.php" class="dash">
          <div class="card-body ">
            <div class="row">
              <div class="col-3">
                <i class="fa fa-user-pen fa-4x" style="color: #ff8e00"></i>
              </div>
              <?php
              $sql = "SELECT id from author";
              $author = $connection->query($sql);
              $author = $author->num_rows;
              ?>
              <div class="col-9 text-right">
                <h2><?php echo htmlentities($author); ?></h2>
                <h5 class="text-secondary"> Total Authors</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-4 col-md-6" style="margin-top: 20px">
      <div class="card shadow-sm p-2 mb-5 bg-white rounded">
        <a href="../admin/books.php" class="dash">
          <div class="card-body ">
            <div class="row">
              <div class="col-3">
                <i class="fa fa-book-open-reader fa-4x" style="color: #ff8e00"></i>
              </div>
              <?php
              $sql = "SELECT id from books ";
              $result = $connection->query($sql);
              $result = $result->num_rows;
              ?>
              <div class="col-9 text-right">
                <h2><?php echo $result ?></h2>
                <h5 class="text-secondary"> Books Listed</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-4 col-md-6" style="margin-top: 20px">
      <div class="card shadow-sm p-2 mb-5 bg-white rounded">
        <a href="../admin/bookcases.php" class="dash">
          <div class="card-body ">
            <div class="row">
              <div class="col-3">
                <i class="fa fa-folder fa-4x" style="color: #ff8e00"></i>
              </div>
              <?php
              $sql = "SELECT id from bookcase";
              $total_bookcases = $connection->query($sql);
              $total_bookcases = $total_bookcases->num_rows;
              ?>
              <div class="col-9 text-right">
                <h2><?php echo htmlentities($total_bookcases); ?></h2>
                <h5 class="text-secondary"> Total Bookcases</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-4 col-md-6" style="margin-top: 20px">
      <div class="card shadow-sm p-2 mb-5 bg-white rounded">
        <a href="../admin/books.php" class="dash">
          <div class="card-body ">
            <div class="row">
              <div class="col-3">
                <i class="fa fa-layer-group fa-4x" style="color: #ff8e00"></i>
              </div>
              <?php
              $sql = "SELECT id from books where heap=1";
              $in_heap = $connection->query($sql);
              $in_heap = $in_heap->num_rows;
              ?>
              <div class="col-9 text-right">
                <h2><?php echo htmlentities($in_heap); ?></h2>
                <h5 class="text-secondary"> Total Books in Heap</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>