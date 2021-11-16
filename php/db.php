<?php 

$configs = include('../config.php');

// Create connection

$conn = new mysqli($configs['g_db_host'],$configs['g_db_username'],$configs['g_db_password'],$configs['g_db_db']);
# $conn = new mysqli($configs['l_db_host'],$configs['l_db_username'],$configs['l_db_password'],$configs['l_db_db']);

// Check connection

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

function fetchAssocStatement($stmt)
{
    if($stmt->num_rows>0)
    {
        $result = array();
        $md = $stmt->result_metadata();
        $params = array();
        while($field = $md->fetch_field()) {
            $params[] = &$result[$field->name];
        }
        call_user_func_array(array($stmt, 'bind_result'), $params);
        if($stmt->fetch())
            return $result;
    }

    return null;
}

?>