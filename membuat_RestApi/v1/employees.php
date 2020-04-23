<?php

include('../koneksi.php');
    
$connection = getConnect(); 
$request_method = $_SERVER['REQUEST_METHOD']; 

//untuk select tabel_employee dari database
function get_employees($id = 0) {
    global $connection; 
    $querysql = "SELECT * FROM `tabel_employee`"; 

    if ($id != 0) {
        $querysql .= " WHERE tabel_employee.id = $id;";        
    }
    $respons = array(); 
    $resultdata = mysqli_query($connection, $querysql); 
    
    while ($row = mysqli_fetch_assoc($resultdata)) { 
        $respons[] = $row;
    }
    header("Content-Type:application/json");
    echo json_encode($respons); 
}
//untuk menambah employee

function tambah_employee() {
    global $connection; 
    $data = json_decode(file_get_contents("php://input"), true); 

    $employee_name=$data["employee_name"];
    $employee_salary=$data["employee_salary"];
    $employee_age=$data["employee_age"];

    $querysql ="INSERT INTO `tabel_employee`(`id`, `employee_name`, `employee_salary`, `employee_age`) 
    VALUES  (NULL, '$employee_name', '$employee_salary', '$employee_age')";

    if (mysqli_query($connection, $querysql)) {
        $respons = array('status' => 1, 'status_message' => 'Employee Added Succesfully');
    } else {
        $respons = array('status' => 0, 'status_message' => 'Employee Added Failed');
    }
    header('Content-Type:application/json');
    echo json_encode($respons);
}

// untuk update employee
function update_employee($id) {
    
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true); 

    $employee_name=$data["employee_name"];
    $employee_salary=$data["employee_salary"];
    $employee_age=$data["employee_age"];
   
    $querysql= "UPDATE `tabel_employee` SET `employee_name` = '$employee_name', `employee_salary` = '$employee_salary', `employee_age` = '$employee_age' WHERE `tabel_employee`.`ID` = $id;";

    if (mysqli_query($connection, $querysql)) {
        $respons = array('status' => 1, 'status_message' => 'Employee Update Succesfully');
    } else {
        $respons = array('status' => 0, 'status_message' => 'Employee Update Failed');
    }
    header("Content-Type:application/json");
    echo json_encode($respons);
}

//untuk menghapus employee
function hapus_employee($id) {
    global $connection;
    
    $querysql = "DELETE FROM `tabel_employee` WHERE `tabel_employee`.`id` = $id";

    if (mysqli_query($connection, $querysql)) {
        $respons = array('status' => 1, 'status_message' => 'Employee Delete Succesfully');
    } else {
        $respons = array('status' => 0, 'status_message' => 'Employee Delete Failed');
    }
    header("Content-Type:application/json");
    echo json_encode($respons);

}

switch ($request_method) {

    case "GET":
            
        if (!empty($_GET["id"])) {
            get_employees(intval($_GET["id"])); 
        } else {
            get_employees(); 
        }
        break;

    case 'POST':
            tambah_employee();
            break;

    case 'PUT':
          $id = intval($_GET["id"]);
          update_employee($id); 
          break;


    case 'DELETE':
          $id = intval($_GET["id"]);
          hapus_employee($id);
          break;

          default:
          header("HTTP/1.0 405 Method Not Allowed");
          break;


}




?>