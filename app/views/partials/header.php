<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $title; ?></title>

    <link href="public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="public/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="public/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <style>
        /* Ajuste para que el contenido no pegue al borde superior ahora que no hay sidebar */
        #content-wrapper {
            background-color: #ffffffff;
        }
        .navbar-nav .nav-link {
            font-weight: 600;
            color: #d17700;
        }
        .navbar-brand-custom {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #d17700 !important;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    
                    <a class="navbar-brand navbar-brand-custom mr-4" href="index.php">
                        <i class="fas fa- glass-cheers mr-2"></i> Fiesta De Lujo
                    </a>

                    <ul class="navbar-nav d-none d-md-flex">
                        <li class="nav-item">
                            <a class="nav-link" href="?">
                                <i class="fas fa-fw fa-desktop mr-1"></i> Escritorio
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a class="nav-link" href="?route=registro&method=registro">
                                <i class="fas fa-fw fa-book mr-1"></i> Registro
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['row_count_0']['username']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="public/img/undraw_profile_2.svg">
                            </a>
                            
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Ajustes
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Salir
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
                <div class="container-fluid">