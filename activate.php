<?php
include "includes/conn.php";
$conn = new Conn();
$title = "Activar Cuenta - DBService";
include "includes/header.php";
include "includes/modal-diss.html";
echo '<section class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="view4">
            <h1>Gracias por Confirmar tu Registro.</h1>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</section>';

$activate = $_SERVER["REQUEST_URI"];
$urlArray = explode('/', $activate);
$hash = $urlArray[3];
$id = $urlArray[4];

$stmt = $conn->prepare("SELECT * FROM contacto WHERE id=?;");
$stmt->execute([$id]);
if ($stmt->rowCount() > 0)
{
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    if ($row->hash == $hash)
    {		
        $stmt = $conn->prepare("UPDATE contacto SET hash=NULL, active=true WHERE id=?;");
        $stmt->execute([$id]);
        echo'<script>window.addEventListener("DOMContentLoaded", function() {
        toast(0, "Cuenta Activada:", "Gracias por Activar tu Cuenta.\nYa puedes Loguearte y Empezar a Usar el Sitio.");
        });</script>';
    }
    else
    {
        echo'<script>window.addEventListener("DOMContentLoaded", function() {
        toast(1, "Cuenta Ya Activada", "Ya Has Activado tu Cuenta. Por Favor Entra con tu E-mail y Contraseña y Descarta el E-mail de Confirmación.");
        });</script>';
    }
}

include "includes/footer.html";
?>