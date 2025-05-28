<?php
include "includes/conn.php";
define('BASE_URL', 'http://localhost:8080/DBService/');

$conn = new Conn();
header("Content-Type:application/json");

function response($status, $message, $data = null) {
    http_response_code($status);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

switch (true) {
    // CREATE
    case isset($_POST["create"]):
        $name    = $_POST["name"];
        $surname = $_POST["surname"];
        $phone   = $_POST["phone"];
        $email   = $_POST["email"];
        $hash2 = hash("crc32", $email, false);
        $pass    = $_POST["pass"];
        $bday    = $_POST["bday"];
        $gender  = $_POST["gender"];
        $img     = $_POST["img"] ?? "";
        $tmp     = $_POST["path"] ?? "";
        $path    = "";

        // Verifica unicidad
        $stmt = $conn->prepare("SELECT id FROM contacto WHERE email = :email OR phone = :phone");
        $stmt->execute([':email' => $email, ':phone' => $phone]);
        if ($stmt->rowCount() > 0) {
            response(300, "El Teléfono o la Dirección de E-mail ya Están Registrados, No Se Ha Podido Agregar el Usuario: ", $name);
        }

        // Inserta usuario sin path
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO contacto (name, surname, phone, email, pass, bday, gender, path, hash, active) VALUES (:name, :surname, :phone, :email, :pass, :bday, :gender, :path, :hash, :active)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':phone' => $phone,
            ':email' => $email,
            ':pass' => $hash,
            ':bday' => $bday,
            ':gender' => $gender,
            ':path' => $path,
            ':hash' => $hash2,
            ':active' => false
        ]);
        $id = $conn->lastInsertId();

        if ($id) {
            if ($img && $tmp && file_exists($tmp)) {
                $ruta = "users/$id";
                if (!is_dir($ruta)) mkdir($ruta, 0777, true);
                $path = "$ruta/$img";
                rename($tmp, $path);
                $path = BASE_URL . $path;
            } else {
                $path = BASE_URL . ($gender == 0 ? "imgs/female.jpg" : "imgs/male.jpg");
            }
            $stmt = $conn->prepare("UPDATE contacto SET path = :path WHERE id = :id");
            $stmt->execute([':path' => $path, ':id' => $id]);

            $subject = "Por Favor Contactame en Esta Dirección";
            $message = "<h3>Gracias por registrarte</h3><p>Por Favor haz Click en el Botón Activar mi Cuenta para Empezar a Usar el Sitio.</p><a href='http://localhost:8080/DBService/activate.php/" . $hash2 . "/" . $id . "'><div style='background-color:aquamarine; border:thin; width:120px; height:60px; text-align:center;'>Quiero Activar mi Cuenta</div></a><br><br><small>Copyright © 2025 César Matelat <a href='mailto:matelat@gmail.com'>matelat@gmail.com</a></small>";
            $server_email = "matelat@gmail.com";
            $headers  = "From: $server_email" . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            if(mail($email, $subject, $message, $headers))
            {
                echo 'Se ha enviado un mensaje a tu cuenta de E-mail.';
            }
            else
            {
                echo "Error al enviar el mensaje si vuelves a intentarlo y vuelve a dar error, por favor escribe a matelat@gmail.com";
            }

            response(200, "Se Ha Agregado Correctamente El Usuario: ", $name);
        } else {
            response(300, "Algo Ha Fallado, No Se Ha Podido Agregar el Usuario: ", $name);
        }
        break;

    // READ
    case isset($_POST["read"]):
        $id = $_POST["id"] ?? "";
        if ($id != "") {
            $stmt = $conn->prepare("SELECT * FROM contacto WHERE id=:id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                response(200, "El Usuario con ID: $id es: ", $data);
            } else {
                response(300, "No Existe el Usuario con ID: $id");
            }
        } else {
            $stmt = $conn->query("SELECT * FROM contacto");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                response(200, "Esta es la Lista Completa de Usuarios: ", $data);
            } else {
                response(300, "No Hay Datos Aun en la Base de Datos");
            }
        }
        break;

    // UPDATE
    case isset($_POST["update"]):
        $id      = $_POST["id"];
        $name    = $_POST["name"];
        $surname = $_POST["surname"];
        $phone   = $_POST["phone"];
        $email   = $_POST["email"];
        $pass    = $_POST["pass"];
        $bday    = $_POST["bday"];
        $gender  = $_POST["gender"];
        $img     = $_POST["img"] ?? "";
        $path    = $_POST["path"] ?? "";

        // Verifica unicidad
        $stmt = $conn->prepare("SELECT id FROM contacto WHERE (email = :email OR phone = :phone) AND id != :id");
        $stmt->execute([':email' => $email, ':phone' => $phone, ':id' => $id]);
        if ($stmt->rowCount() > 0) {
            response(300, "El Teléfono o la Dirección de E-mail ya Están Registrados, No Se Ha Podido Modificar el Usuario: ", $name);
        }

        $stmt = $conn->prepare("SELECT * FROM contacto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) response(300, "No Existe El Usuario con ID: ", $id);

        $fields = [];
        $params = [':id' => $id];

        if ($name !== $user['name'])     { $fields[] = "name = :name";         $params[':name'] = $name; }
        if ($surname !== $user['surname']) { $fields[] = "surname = :surname"; $params[':surname'] = $surname; }
        if ($phone !== $user['phone'])   { $fields[] = "phone = :phone";       $params[':phone'] = $phone; }
        if ($email !== $user['email'])   { $fields[] = "email = :email";       $params[':email'] = $email; }
        if ($pass !== "")                { $fields[] = "pass = :pass";         $params[':pass'] = password_hash($pass, PASSWORD_DEFAULT); }
        if ($bday !== $user['bday'])     { $fields[] = "bday = :bday";         $params[':bday'] = $bday; }
        if ($gender !== $user['gender']) { $fields[] = "gender = :gender";     $params[':gender'] = $gender; }
        if ($path !== $user['path'])     { $fields[] = "path = :path";         $params[':path'] = $path; }

        if (empty($fields)) response(200, "No hay cambios para actualizar en el usuario: ", $name);

        $sql = "UPDATE contacto SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            response(200, "Se Ha Modificado Correctamente El Usuario: ", $name);
        } else {
            response(300, "No se realizaron cambios en el usuario con ID: ", $id);
        }
        break;

    // DELETE
    case isset($_POST["delete"]):
        $id = $_POST["id"];
        $stmt = $conn->prepare("DELETE FROM contacto WHERE id=:id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $ruta = "users/$id";
            if (is_dir($ruta)) {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ruta, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
                    $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
                }
                rmdir($ruta);
            }
            $conn->exec("SET @count = 0; UPDATE contacto SET id = @count:= @count + 1; ALTER TABLE contacto AUTO_INCREMENT = 1;");
            response(200, "Se Ha Borrado Correctamente El Usuario con ID: ", $id);
        } else {
            response(300, "No Existe El Usuario con ID: ", $id);
        }
        break;

    // LOGIN
    case isset($_POST["login"]):
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $pass  = $_POST["pass"];

        $sql = "SELECT active FROM contacto WHERE email='$email';";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0)
        {
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            if ($row->active)
            {
                $stmt = $conn->prepare("SELECT * FROM contacto WHERE email = :email");
                $stmt->bindValue(':email', $email);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                if ($row && password_verify($pass, $row->pass)) {
                    response(200, "Bienvenido Usuario: ", [
                        "id"      => $row->id,
                        "name"    => $row->name,
                        "surname" => $row->surname,
                        "phone"   => $row->phone,
                        "email"   => $row->email,
                        "bday"    => $row->bday,
                        "gender"  => $row->gender,
                        "path"    => $row->path
                    ]);
                } else {
                    response(300, "No se ha Encontrado el E-mail: ", $email . " en la Base de Datos o la Contraseña no es Correcta.");
                }
            }
            else
            {
                response(300, "Por Favor Revisa tu Bandeja de Entrada y Haz Click en el Enlace en el E-mail de Confirmación.");
            }
        }
        else
        {
            response(300, "No Existe el Usuario con E-mail: ", $email);
        }
        break;

    // FORGET PASSWORD
    case isset($_POST["forget"]):
        $email = $_POST["email"];
        $stmt = $conn->prepare("SELECT email FROM contacto WHERE email=:email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($user && $user->email == $email) {
            $hash = password_hash("1111", PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE contacto SET pass=:pass WHERE email=:email");
            $stmt->execute([':pass' => $hash, ':email' => $email]);
            response(200, "Se Ha Creado la Contraseña Provisoria 1111, Logueate y Cámbiala Inmediatamente", "Gracias por Usar Este Servicio.");
        } else {
            response(300, "No se ha Encontrado el E-mail: ", $email);
        }
        break;
}
?>