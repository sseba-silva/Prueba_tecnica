Proyecto de Gestión de Productos

Este proyecto es una API desarrollada en PHP 8.2 que permite gestionar productos, bodegas, sucursales, monedas y materiales. La base de datos está en MySQL/MariaDB 10.4.32 y se ejecuta con XAMPP.

* Requisitos Previos

PHP 8.2 instalado.

XAMPP (para ejecutar MySQL/MariaDB y Apache).

MySQL/MariaDB 10.4.32 (incluido en XAMPP).

* Configuración del Proyecto

Clonar el Repositorio

Abre una terminal y clona el repositorio:

    git clone https://github.com/sseba-silva/Prueba-tecnica.git

Después de clonar, accede al proyecto:

    cd Prueba-tecnica

Configurar el Servidor Local

Mover los archivos al directorio de XAMPP

Copia o mueve los archivos del repositorio a la carpeta de XAMPP:

    C:\xampp\htdocs\proyecto

Si ya clonaste directamente en htdocs, omite este paso.

Configurar la Base de Datos

* Iniciar XAMPP

Abre XAMPP.

Activa los módulos Apache y MySQL.

* Crear la Base de Datos

Abre http://localhost/phpmyadmin/ en tu navegador.

Ve a SQL , Importa el script SQL desde el repositorio:


Selecciona el archivo productos.sql.

Haz clic en Continuar.

* Configurar la Conexión a la Base de Datos

Abre el archivo config.php y ajusta las credenciales si es necesario:

<?php
$servername = "localhost";
$username = "root";
$password = ""; // Deja vacío si usas XAMPP por defecto
$dbname = "productos_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

Si tu MySQL tiene una contraseña, colócala en $password.

* Iniciar el Servidor Local

Abre XAMPP y asegúrate de que los servicios de Apache y MySQL están activos.

Luego, accede al proyecto en tu navegador:
http://localhost/proyecto/api.php

Ya en esa pestaña puedes probar el formulario.
