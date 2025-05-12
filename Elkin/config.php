<?php
//Modificacion Hecha por elkin :P
    //añado Return para devolver a phpmailer y El archivo
    //recuperar-contraseña.php para que no se rompa el flujo de la aplicacion
    //devolviendo datos de la base de datos y credenciales de phpmailer


    return [
        'base_url' => 'http://localhost/billboot/',
        'app_name' => 'billboot',
        'db' => [
            'host' => 'localhost',
            'dbname' => 'bill_bot',
            'user' => 'root',
            'password' => '',
        ],
        'mail' => [
            'host' => 'smtp.gmail.com',  // Cambia esto según tu proveedor de correo
            'username' => 'elkinmb3@gmail.com',  // Tu correo electrónico
            'password' => 'kvfu plfo zprd rqfm',  // Tu contraseña o contraseña de aplicación
            'port' => 587,  // Puerto SMTP (587 para TLS, 465 para SSL)
            'encryption' => 'tls',  // 'tls' o 'ssl'
            'from_email' => 'elkinmb3@gmail.com',  // Correo remitente
            'from_name' => 'Bill Bot Technology',  // Nombre remitente
        ],
    ];
    ?>