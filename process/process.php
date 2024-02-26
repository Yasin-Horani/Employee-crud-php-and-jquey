<?php
error_reporting(0);
header('Content-Type: application/json');
require_once('db.php');

if ($_POST['act'] == 'all') {
    $request = $_REQUEST;
    $search = $request['search'];

    //Check if the search query is empty
    //if (empty($search) || strlen($search) < 3) {
    if (empty($search)) {
        $sql = "SELECT * FROM employees";
    } else {
        $sql = "SELECT * FROM employees WHERE 
            id LIKE '%" . $search . "%' OR 
            first_name LIKE '%" . $search . "%' OR 
            last_name LIKE '%" . $search . "%' OR 
            email LIKE '%" . $search . "%' OR 
            address LIKE '%" . $search . "%'";
    }

    $results = $mysqli->query($sql);
    $row = $results->fetch_all(MYSQLI_ASSOC);
    $results->free_result();
    $mysqli->close();

    $return_arr["row"] = $row;
    echo json_encode($return_arr);
}

//
if ($_POST['act'] == 'save') {
    $request = $_REQUEST;
    $email = $request['email'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $address = $request['address'];
    $gender = $request['gender'];


    $sql = "INSERT INTO employees (email, first_name, last_name, `address`, gender)
        VALUES ('" . $email . "', '" . $first_name . "', '" . $last_name . "', '" . $address . "', '" . $gender . "') ";
    if ($mysqli->query($sql)) {
        $response = array('status' => 'success', 'message' => 'Employee has been created successfully.');
    } else {
        $response = array('status' => 'error', 'message' => "Error: " . $sql . "<br>" . $mysqli->error);
    }
    $mysqli->close();
    echo json_encode($response);
}
//
if ($_POST['act'] == 'delete') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM employees WHERE id = $id";
        if ($mysqli->query($sql)) {
            $response = array('status' => 'success', 'message' => 'Employee has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => "Error: " . $sql . "<br>" . $mysqli->error);
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Employee ID is missing in the request.');
    }
    $mysqli->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
// update
if ($_POST['act'] == 'update') {
    $request = $_REQUEST;
    $id = $_POST['id'];
    $email = $request['email'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $address = $request['address'];
    $gender = $request['gender'];

    $sql = "UPDATE employees 
    SET 
        first_name = '" . $first_name . "', 
        last_name = '" . $last_name . "', 
        `address` = '" . $address . "' ,
        email = '" . $email . "',
        gender = '" . $gender . "'
    WHERE 
        id = '" . $id . "'";


    if ($mysqli->query($sql)) {

        $response = array('status' => 'success', 'message' => 'Employee has been created successfully.');
    } else {
        $response = array('status' => 'error', 'message' => "Error: " . $sql . "<br>" . $mysqli->error);
    }
    $mysqli->close();
    echo json_encode($response);
}
// update
/*
if ($_POST['act'] == 'search') {
    $request = $_REQUEST;
    $search = $request['query'];

    // Check if the search query is empty
    if (empty($search)) {
        // If empty, fetch all employee records
        $sql = "SELECT * FROM employees";
    } else {
        // If not empty, perform a search based on the query
        $sql = "SELECT * FROM employees WHERE 
            id LIKE '%" . $search . "%' OR 
            first_name LIKE '%" . $search . "%' OR 
            last_name LIKE '%" . $search . "%' OR 
            email LIKE '%" . $search . "%' OR 
            address LIKE '%" . $search . "%'";
    }

    $results = $mysqli->query($sql);
    $row = $results->fetch_all(MYSQLI_ASSOC);
    $results->free_result();
    $mysqli->close();

    $return_arr["row"] = $row;
    echo json_encode($return_arr);
}
*/
