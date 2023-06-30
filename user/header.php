<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.101.0">
    <title>User Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.png">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">

    <style>
        #navbarCollapse ul li a:hover,
        .nav-link.active {
            border-bottom: 3px solid #ff8e00;
            padding-bottom: 1px;
        }

        #navbarCollapse ul li a:hover {
            color: #f9b117;
        }

        .page-item.active .page-link {
            background-color: #003049 !important;
            border: #003049 1px solid !important;
        }

        table.dataTable thead tr th,
        table.dataTable thead tr td {
            color: #003049 !important;
            border-bottom: #ff8e00 1px solid;
        }

        .breadcrumb-item>a {
            color: #ff8e00 !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>

    <header>
        <nav class="navbar navbar-dark navbar-expand-sm fixed-top" style="background-color: #003049">
            <div class="container">
                <a href="../user/index.php" class="navbar-brand">
                    <img src="../assets/images/logo-dark.png" alt="Helios Logo" height="25">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div id="navbarCollapse" class="collapse navbar-collapse ml-auto">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="../user/index.php" class="nav-link <?= ($activePage == 'index') ? 'active' : ''; ?>">
                                My bookcases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../user/books.php" class="nav-link <?= ($activePage == 'books') ? 'active' : ''; ?>">
                                My books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../user/heap.php" class="nav-link <?= ($activePage == 'heap') ? 'active' : ''; ?>">
                                Books in heap
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../pages/logout.php" class="nav-link">
                                Logout <i class="fa fa-sign-out"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <script>
        jQuery(function($) {
            $('.menu ul li a').filter(function() {
                var locationUrl = window.location.href;
                var currentItemUrl = $(this).prop('href');

                return currentItemUrl === locationUrl;
            }).parent('li').addClass('active');
        });
    </script>
</body>
</html>