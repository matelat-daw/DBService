<?php
session_start();
ob_start();
include "includes/callService.php";

$serviceBaseUrl = "http://localhost:8080/DBService/service.php";

if (empty($_SESSION["index"])) {
    $_SESSION["index"] = 1;
}
setlocale(LC_ALL, 'spanish');

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

switch (true) {
    case isset($_POST["create"]):
        $postData = [
            'create'  => 1,
            'name'    => $_POST["username"],
            'surname' => $_POST["surname"],
            'phone'   => $_POST["phone"],
            'email'   => $_POST["email"],
            'pass'    => $_POST["pass"],
            'bday'    => $_POST["bday"],
            'gender'  => $_POST["gender"]
        ];

        if (!empty($_FILES["profile"]["name"])) {
            $img = $_FILES["profile"]["name"];
            $tmp = $_FILES["profile"]["tmp_name"];
            $ruta = "tmp";
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $path = $ruta . "/" . basename($img);
            move_uploaded_file($tmp, $path);
            $postData['img'] = $img;
            $postData['path'] = $path;
        }

        $data = callService($serviceBaseUrl, $postData);
        break;

    case isset($_POST["read"]):
        $id = $_POST["id"] ?? "";
        $postData = [
            'read' => 1,
            'id'   => $id
        ];
        $data = callService($serviceBaseUrl, $postData);
        break;

    case isset($_POST["update"]):
        $id = $_POST["id"];
        $name = $_POST["username"];
        $surname = $_POST["surname"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $bday = $_POST["bday"];
        $gender = $_POST["gender"];
        $img = $_FILES["profile"]["name"];
        $tmp = $_FILES["profile"]["tmp_name"];
        $path = $_POST["path"];

        if ($img != "") {
            $ruta = "users/" . $id;
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $path = $ruta . "/" . basename($img);
            move_uploaded_file($tmp, $path);
            $path = $serviceBaseUrl . '/../' . $path; // Ajusta si tu BASE_URL cambia
        }

        $postData = [
            'update'  => 1,
            'id'      => $id,
            'name'    => $name,
            'surname' => $surname,
            'phone'   => $phone,
            'email'   => $email,
            'pass'    => $pass,
            'bday'    => $bday,
            'gender'  => $gender,
            'img'     => $img,
            'path'    => $path
        ];
        $data = callService($serviceBaseUrl, $postData);
        break;

    case isset($_POST["delete"]):
        $postData = [
            'delete' => 1,
            'id'     => $_POST["id"]
        ];
        $data = callService($serviceBaseUrl, $postData);
        break;

    case isset($_POST["login"]):
        $postData = [
            'login' => 1,
            'email' => $_POST["email"],
            'pass'  => $_POST["pass"]
        ];
        $data = callService($serviceBaseUrl, $postData);
        break;
}

$title = "Servicio de CRUD a la Base de Datos";
include "includes/header.php";
include "includes/modal-img.html";
include "includes/modal.html";
include "includes/nav.html";
?>
<section class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="view1">
                <br><br>
                <h1 class="center">CRUD a la Base de Datos a Través de un Servicio</h1>
                <br>
                <h2>Primer Servicio CREATE - Inserta un Usuario en la Base de Datos</h2>
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return verify(1)">
                    <label><input type="text" name="username" required> Nombre del Usuario.</label><br><br>
                    <label><input type="text" name="surname" required> Apellidos del Usuario.</label><br><br>
                    <label><input type="number" name="phone" required> Teléfono del Usuario.</label><br><br>
                    <label><input type="email" name="email" required> E-mail del Usuario.</label><br><br>
                    <label><input type="password" id="pass" name="pass" required> Contraseña</label><br><br>
                    <label><input type="password" id="pass2" required> Repite Contraseña</label><br><br>
                    <label><input type="date" name="bday" required> Fecha de Nacimiento</label><br><br>
                    <label><input type="radio" name="gender" value="0" checked> Mujer</label><br><br>
                    <label><input type="radio" name="gender" value="1"> Varón</label><br><br>
                    <label><input type="file" name="profile" class="btn btn-primary btn-lg"> Foto de Perfil<small>(opcional)</small></label><br><br>
                    <input type="submit" name="create" value="Inserta Usuario" class="btn btn-info btn-lg">
                </form>
                <br><br>
                <?php
                if (isset($_POST["create"])) {
                    session_unset();
                    echo "<h3 class='blue'>{$data["message"]}{$data["data"]}</h3>";
                }
                ?>
            </div>
            <div id="view2">
                <br><br><br>
                <h2>Segundo Servicio READ - Consulta de Usuarios por ID, en Blanco Muestra Todos los Usuarios.</h2>
                <form action="" method="post">
                    <label><input type="number" name="id" min="1"> ID del Registro a Consultar.</label><br><br>
                    <input type="submit" name="read" value="Consulta" class="btn btn-success btn-lg"><br><br>
                </form>
                <?php
                if (isset($_POST["read"])) {
                    $id = $_POST["id"];
                    if ($id != "") {
                        session_unset();
                        if ($data["status"] == 200) {
                            $html = "<h3 class='blue'>{$data["message"]}</h3>";
                            $html .= "<table><tr><th>Nombre</th><th>Apellido</th><th>Teléfono</th><th>E-mail</th><th>Fecha de Nacimiento</th><th>Genero</th><th>Imagen de Perfil</th></tr>";
                            $html .= "<tr><td>{$data["data"][0]["name"]}</td>";
                            $html .= "<td>{$data["data"][0]["surname"]}</td>";
                            $html .= "<td>{$data["data"][0]["phone"]}</td>";
                            $html .= "<td>{$data["data"][0]["email"]}</td>";
                            $date = explode("-", $data["data"][0]["bday"]);
                            $dateObj = DateTime::createFromFormat('!m', $date[1]);
                            $monthName = strftime('%B', $dateObj->getTimestamp());
                            $html .= "<td>{$date[2]}/{$monthName}/{$date[0]}</td>";
                            $html .= "<td>" . ($data["data"][0]["gender"] == 0 ? "Femenino" : "Masculino") . "</td>";
                            $html .= "<td><a href='javascript:showImg(\"{$data["data"][0]["path"]}\")'><img src='{$data["data"][0]["path"]}' width='160' height='80'></a></td></tr></table>";
                            echo $html;
                        } else {
                            echo "<h3 class='red'>{$data["message"]}</h3>";
                        }
                    } else {
                        if ($data["status"] == 200) {
                            $length = count($data["data"]);
                            $js = "<script>";
                            $js .= "var length = $length;";
                            $js .= "var id = [], username = [], surname = [], phone = [], email = [], bday = [], genre = [], img = [];";
                            for ($i = 0; $i < $length; $i++) {
                                $js .= "id[$i] = '{$data["data"][$i]["id"]}';";
                                $js .= "username[$i] = '{$data["data"][$i]["name"]}';";
                                $js .= "surname[$i] = '{$data["data"][$i]["surname"]}';";
                                $js .= "phone[$i] = '{$data["data"][$i]["phone"]}';";
                                $js .= "email[$i] = '{$data["data"][$i]["email"]}';";
                                $js .= "bday[$i] = '{$data["data"][$i]["bday"]}';";
                                $js .= "genre[$i] = '{$data["data"][$i]["gender"]}';";
                                $js .= "img[$i] = '{$data["data"][$i]["path"]}';";
                            }
                            $js .= "</script>";
                            echo $js;
                            ?>
                            <div id="table"></div>
                            <br>
                            <span id="page"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="javascript:prev()" id="prev" class="btn btn-danger btn-lg">Anteriores Resultados</button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="javascript:next()" id="next" class="btn btn-primary btn-lg">Siguientes Resultados</button><br>
                            <script>change(1, 5, length);</script>
                            <?php
                        } else {
                            echo "<h3 class='red'>{$data["message"]}</h3>";
                        }
                    }
                }
                ?>
            </div>
            <div id="view3">
                <br><br><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <h2>Tercer Servicio UPDATE - Modifica Datos de un Usuario Previo Login de Usuario</h2>
                        <form action="" method="post">
                            <label><input type="email" name="email" required> E-mail de Usuario</label><br><br>
                            <label><input type="password" name="pass" required> Contraseña</label><br><br>
                            <input type="submit" name="login" value="Login" class="btn btn-secondary btn-lg">
                        </form>
                        <small><a href="forget.php">Olvidé mi Contraseña</a></small>
                        <br><br>
                        <?php
                        if (isset($_POST["login"])) {
                            session_unset();
                            if ($data["status"] == 200) {
                                $id = $data["data"]["id"];
                                $name = $data["data"]["name"];
                                $surname = $data["data"]["surname"];
                                $phone = $data["data"]["phone"];
                                $email = $data["data"]["email"];
                                $bday = $data["data"]["bday"];
                                $date = date('Y-m-d', strtotime($bday));
                                $gender = $data["data"]["gender"];
                                $path = $data["data"]["path"];
                                $pass = $pass ?? '';
                                $html = "<h3 class='blue'>{$data["message"]}{$name}</h3>";
                                $html .= "<img src='{$data['data']["path"]}' alt='Imagen de Perfil' width='320' height='240'><br><br>";
                                $html .= "<button onclick='showIt()' class='btn btn-warning btn-lg'>Modifica Mis Datos</button>";
                                $html .= '<br><br>
                                    <form action="" method="post" style="display:inline;">
                                        <button type="submit" name="logout" class="btn btn-danger">Cerrar Sesión</button>
                                    </form>';
                                echo $html;
                            } else {
                                echo "<h3 class='red'>{$data["message"]}{$data["data"]}</h3>";
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-6 part" id="update" style="visibility: hidden;">
                        <h2>Modifica los Datos de un Usuario</h2>
                        <br>
                        <?php
                        $pass = $pass ?? '';
                        echo '<form action="" method="post" enctype="multipart/form-data" onsubmit="return verify(2)">
                            <label><input type="hidden" name="id" value="' . $id . '"> ID del Usuario = ' . $id . '</label><br><br>
                            <label><input type="text" name="username" value="' . $name . '" required> Nombre del Usuario.</label><br><br>
                            <label><input type="text" name="surname" value="' . $surname . '" required> Apellidos del Usuario.</label><br><br>
                            <label><input type="number" name="phone" value="' . $phone . '" required> Teléfono del Usuario.</label><br><br>
                            <label><input type="email" name="email" value="' . $email . '" required> E-mail del Usuario.</label><br><br>
                            <label><input type="password" id="pass3" name="pass" value="' . $pass . '"> Contraseña</label><br><br>
                            <label><input type="password" id="pass4" value="' . $pass . '"> Repite Contraseña</label><br><br>
                            <label><input type="date" name="bday" value="' . $date . '" required> Fecha de Nacimiento</label><br><br>';
                        if ($gender == 0) {
                            echo '<label><input type="radio" name="gender" value="0" checked> Mujer</label><br><br>
                                <label><input type="radio" name="gender" value="1"> Varón</label><br><br>';
                        } else {
                            echo '<label><input type="radio" name="gender" value="0"> Mujer</label><br><br>
                                <label><input type="radio" name="gender" value="1" checked> Varón</label><br><br>';
                        }
                        echo '<label><input type="file" name="profile" class="btn btn-primary btn-lg"> Foto de Perfil<small>(opcional)</small></label>
                            <input type="hidden" name="path" value="' . $path . '"><br><br>
                            <input type="submit" name="update" value="Modifica Usuario" class="btn btn-info btn-lg">
                        </form>';
                        ?>
                    </div>
                    <?php
                    if (isset($_POST["update"])) {
                        session_unset();
                        echo "<h3 class='blue'>{$data["message"]}{$data["data"]}</h3>";
                    }
                    ?>
                </div>
            </div>
            <div id="view4">
                <br><br><br><br>
                <h2>Cuarto Servicio DELETE - Borra un Usuario</h2>
                <form action="" method="post">
                    <label><input type="number" name="id" min="1" required> ID del Usuario a Borrar.</label><br><br>
                    <input type="submit" name="delete" value="Borra Usuario">
                </form>
                <br><br>
                <?php
                if (isset($_POST["delete"])) {
                    session_unset();
                    echo "<h3 class='red'>{$data["message"]}{$data["data"]}</h3>";
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