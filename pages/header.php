<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.101.0">
    <title>User Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
            color: #f9b117 !important;
        }

        .btn-outline-primary{
            border: 1px solid #ff8e00;
            color: #ff8e00;
        }

        div.card > .border-primary {
            border: 1px solid #ff8e00;
        }

        .btn-outline-primary:hover, .btn-primary {
            border: 1px solid #ff8e00;
            background-color: #ff8e00;
        }

        .btn-primary:hover {
            border: 1px solid #ff8400;
            background-color: #ff8400;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>

    <header>
        <nav class="navbar navbar-dark navbar-expand-sm fixed-top" style="background-color: #003049">
            <div class="container">
                <a href="../user/index.php" class="navbar-brand">
                    <img src="../assets/images/logo-dark.png" alt="" height="25">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div id="navbarCollapse" class="collapse navbar-collapse ml-auto">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="../pages/index.php" class="nav-link <?= ($activePage == 'register') ? 'active' : ''; ?>">
                                <i class="fa fa-user"></i> Create Account
                            </a>
                        </li>
                        <li class="nav-item" >
                            <a href="../index.php" class="nav-link <?= ($activePage == 'login') ? 'active' : ''; ?>" style="color:#ff8e00" >
                                <i class="fa fa-sign-in"></i> Login
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