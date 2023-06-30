<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.101.0">
  <title>Admin Dashboard | Helios Library</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/dselect.css">
</head>

<body style="background-color: #f4f8fc" class="d-flex flex-column min-vh-100">
  <?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>

  <header class="navbar navbar-light sticky-top flex-md-nowrap p-2 shadow bg-light">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="../admin/index.php" style="background-color: transparent"><img src="../assets/images/logo2.png" alt="Helios logo" height="25" class="d-none d-sm-block">
    <img src="../assets/images/logo-dark.png" alt="Helios logo" height="25" class=" d-block d-sm-none"></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background-color: #003049; top: 0rem !important;">
        <div class="position-sticky pt-3 sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item text-light">
              <a class="nav-link <?= ($activePage == 'index') ? 'active' : ''; ?>" aria-current="page" href="index.php">
                <span data-feather="home" class="align-text-bottom"></span>
                <i class="fa fa-dashboard"></i>&nbsp;&nbsp;Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($activePage == 'authors') ? 'active' : ''; ?>" href="authors.php">
                <span data-feather="file" class="align-text-bottom"></span>
                <i class="fa fa-feather"></i>&nbsp;&nbsp;Manage authors
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($activePage == 'genre') ? 'active' : ''; ?>" href="genre.php">
                <span data-feather="file" class="align-text-bottom"></span>
                <i class="fa fa-mask"></i>&nbsp;&nbsp;Manage genre
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($activePage == 'books') ? 'active' : ''; ?>" href="books.php">
                <span data-feather="file" class="align-text-bottom"></span>
                <i class="fa fa-book"></i>&nbsp;&nbsp;Manage books
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($activePage == 'bookcases') ? 'active' : ''; ?>" href="bookcases.php">
                <span data-feather="file" class="align-text-bottom"></span>
                <i class="fa fa-suitcase"></i>&nbsp;&nbsp;Manage bookcase
              </a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?= ($activePage == 'users') ? 'active' : ''; ?>" href="users.php">
                <span data-feather="file" class="align-text-bottom"></span>
                <i class="fa fa-user"></i>&nbsp;&nbsp;Manage users
              </a>
            </li>          
            <li class="nav-item border-top my-3">
              <a class="nav-link" href="../pages/logout.php">
                <span data-feather="file" class="align-text-bottom"></span>
                <i class="fa fa-sign-out"></i>&nbsp;&nbsp;Sign out
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <script>
          jQuery(function($) {
            $('.menu ul li a').filter(function() {
              var locationUrl = window.location.href;
              var currentItemUrl = $(this).prop('href');

              return currentItemUrl === locationUrl;
            }).parent('li').addClass('active');
          });
        </script>