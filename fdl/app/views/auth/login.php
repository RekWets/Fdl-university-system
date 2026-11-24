<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fiesta De Lujo - Iniciar Sesión</title>

    <link href="public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="public/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
        }

        /* Fondo con overlay oscuro para que el formulario resalte */
        .bg-login-image-custom {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('public/img/bg.jpg');
            background-size: cover;
            background-position: center;
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Efecto Glassmorphism para la tarjeta */
        .glass-card {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 20px !important;
        }

        /* Estilo de los inputs */
        .form-control-user {
            background: rgba(255, 255, 255, 0.9) !important;
            border-radius: 10px !important;
            border: none !important;
            transition: all 0.3s;
        }

        .form-control-user:focus {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5) !important;
        }

        /* Botón elegante */
        .btn-lujo {
            background: #c98e0fff; /* Puedes cambiar a un tono dorado si prefieres */
            border: none;
            border-radius: 10px !important;
            padding: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-lujo:hover {
            background: #b87c22ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .welcome-text {
            color: #ffffff;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
    </style>
</head>

<body>
    <div class="bg-login-image-custom"></div>

    <div class="container h-100 d-flex align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card o-hidden border-0 shadow-lg glass-card">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h3 welcome-text">FIESTA DE LUJO</h1>
                                <p class="text-white-50">Ingresa tus credenciales para continuar</p>
                            </div>

                            <form class="user" action="?route=auth&method=login" method="POST">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-user"
                                            placeholder="Correo electrónico o usuario" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user"
                                        placeholder="Contraseña" name="password" required>
                                </div>
                                
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-user btn-block btn-lujo">
                                        Iniciar Sesión
                                    </button>
                                </div>
                            </form>
                            
                            <hr style="border-top: 1px solid rgba(255,255,255,0.2);">
                            
                            <div class="text-center">
                                <small class="text-white">¿Olvidaste tu contraseña? Contacta al administrador.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="public/vendor/jquery/jquery.min.js"></script>
    <script src="public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>