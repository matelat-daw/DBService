<?php
include "includes/callService.php";
$title = "Recuperación de Contraseña";
include "includes/header.php";
include "includes/nav.html";

$serviceBaseUrl = "http://localhost:8080/DBService/service.php";
$data = null;

if (isset($_POST["email"])) {
    $postData = [
        'forget' => 1,
        'email'  => $_POST["email"]
    ];
    $data = callService($serviceBaseUrl, $postData);
}

?>
<section class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-1"></div>
            <div class="col-md-10">
                <div id="view4">
                    <br><br><br><br><br>
                    <h2>Quinto Servicio Recupera Contraseña - Genera la Contraseña 1111 e Informa al Usuario para Cambiarla Inmediatamente.</h2>
                    <form action="" method="post">
                        <label><input type="email" name="email" required> E-mail del Usuario.</label>
                        <br><br>
                        <input type="submit" name="forget" value="Recupera Mi Contraseña">
                    </form>
                    <br><br>
                    <?php
                    if (isset($_POST["forget"]) && isset($data)) {
                        echo "<h3>" . $data["message"] . ", " . $data["data"] . "</h3>";
                    }
                    ?>
                </div>
            </div>
        <div class="col-md-1"></div>
    </div>
</section>
<?php
include "includes/footer.html";
?>