<?php 

include("con_db.php");

    if (strlen($_POST['nombre']) >= 1 && strlen($_POST['apaterno']) >= 1 && strlen($_POST['amaterno']) >= 1 && strlen($_POST['nivel_estudios']) >= 1
    && strlen($_POST['estado']) >= 1 && strlen($_POST['tipo_usuario']) >= 1 && strlen($_POST['email']) >= 1 && strlen($_POST['password']) >= 1) {
        $nombre = trim($_POST['nombre']);
        $apaterno = trim($_POST['apaterno']);
        $amaterno = date("amaterno");
        $nivel_estudios = date("nivel_estudios");
        $estado = date("estado");
        $tipo_usuario = date("tipo_usuario");
        $email = date("email");
        $password = date("password");

        $consulta = "INSERT INTO phi3_usuarios(nombre, apaterno, amaterno, nivel_estudios, estado, tipo_usuario, email, password) 
        VALUES ('$nombre','$apaterno','$amaterno', '$nivel_estudios', '$estado', '$tipo_usuario', '$email', '$password')";

        $resultado = mysqli_query($conex,$consulta);
        if ($resultado) {
            ?> 
            <h3 class="ok">¡Te has inscrito correctamente!</h3>
           <?php
        } else {
            ?> 
            <h3 class="bad">¡Ups ha ocurrido un error!</h3>
           <?php
        }
    }   else {
            ?> 
            <h3 class="bad">¡Por favor complete los campos!</h3>
           <?php
    }

?>