<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
header("Access-Control-Max-Age", "3600");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header("Access-Control-Allow-Credentials", "true");
$result = array();
$result['error'] = false;
$result['message'] = '';


// connect database
$dsn = 'mysql:host=localhost;dbname=students';
$host_user = 'root';
$host_pass = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);


try {

    $con = new PDO($dsn, $host_user, $host_pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result['error'] = false;
    $result['is_db_connected'] = true;
    $result['connection_msg'] = "success connection";
} catch (PDOException) {
    $result['error'] = true;
    $result['is_db_connected'] = false;
    $result['connection_msg'] = "fail connection";
}



// actions
$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    // echo $action . "</br>";
}
// echo $action . "</br>";

if ($action == 'read') {
    $stmt = $con->prepare("SELECT * FROM `infos`");
    $stmt->execute(array());
    $rows = $stmt->rowCount();
    if ($rows < 1) {
        $result['error'] = true;
        $result['message'] = 'No Student Added...';
    }

    $stdArr = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($stdArr, $rows);
    }
    $result['students'] = $stdArr;
}


if ($action == 'add') {
    if (isset($_POST["name"])) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        // 
        $up = $con->prepare("SELECT * FROM `infos` WHERE email = ?");
        $up->execute(array($email));
        $rowscount = $up->rowCount();
        if ($rowscount < 1) {
            $add = $con->prepare("INSERT INTO infos ( name, email, phone) VALUES ( ? , ?, ?)");
            $add->execute(array($name, $email, $phone));
            if ($add) {
                $result["message"] = "New Student Added";
            } else {
                $result["error"] = true;
                $result["message"] = "Fail To Add New Student";
            }
        } else {
            $result["error"] = true;
            $result["message"] = "This email " .  $email . " Is Exist";
        }
    }
}

if ($action == 'update') {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $up = $con->prepare("SELECT * FROM `infos` WHERE email= ? AND id != ?");
        $up->execute(array($email, $id));
        $rowscount = $up->rowCount();
        // echo $rowscount;
        if ($rowscount < 1) {
            $update = $con->prepare("UPDATE infos SET name= ?, email= ?, phone=? WHERE id= ?");
            $update->execute(array($name, $email, $phone, $id));
            if ($update) {
                $result["message"] = "Update Succeed";
            } else {
                $result["error"] = true;
                $result["message"] = "Update Failed";
            }
        } else {
            $result["error"] = true;
            $result["message"] = "This email " .  $email . " Is Exist";
        }
    }
}

// echo $_REQUEST['action'];
if ($action == 'delete') {
    // print_r($_SERVER["REQUEST_METHOD"]);
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $name = $_POST['name'];
        $dell = $con->prepare("SELECT * FROM infos WHERE id= $id");
        $dell->execute();
        $rows = $dell->rowCount();
        if ($rows >= 1) {
            $delete = $con->prepare("DELETE FROM infos WHERE id= ?");
            $delete->execute(array($id));
            if ($delete) {
                $result["message"] = "Success delete of " . $name;
            } else {
                $result["error"] = true;
                $result["message"] = "fail delete";
            }
        } else {
            $result["error"] = true;
            $result["message"] = "Record not exist";
        }
    }




    // echo $_POST["id"];
    // $check = $con->prepare("SELECT * FROM infos WHERE id= ?");
    // $check->execute(array($id));
    // $rows = $check->rowCount();
    // echo $rows;
}

if ($action == 'deleteAll') {


    $stmt2 = $con->prepare(" DELETE FROM infos ");
    $stmt2->execute();
    if ($stmt2) {
        $result["message"] = "Successfuly Deleted Students";
    } else {
        $result['error'] = true;
        $result["message"] = "Failed Deleted Students";
    }
}

echo json_encode($result);
