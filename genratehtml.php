<?php

// Server-side JSON response generation script
include "../login/userdetails.php";
session_start();
include "../config/config.php";
header('Content-Type: application/json');

if (isset($_SESSION['LOGINUSER'])) {
    $obj = $_SESSION['LOGINUSER'];
    $user_id = $obj->pk;
} else {
    echo json_encode(['error' => "LOGIN REQUIRED."]);
    exit;
}

// Global variables
$global_data = [
    'table_count' => null,
    'num_page' => null,
    'title' => null,
    'header' => null,
    'shuffled_content' => null,
    'size' => null,
    'fontcolor' => null,
    'imagename' => null
];

// Function to generate the HTML dynamically
function generateDynamicHTML($data) {
    $html = "<!DOCTYPE html>";
    $html .= "<html>\n<head>\n<style>\n@page { size: A4; margin: 0; }\nbody { margin: 0; padding: 0; background-color: #fff; }\n.page { background-image: url('http://192.168.1.6/wsearch/bcard/bcard-images/" . $data['imagename'] . "'); background-repeat: no-repeat; }\n.table-container { padding: 10px; }\ntable.inner-table { border-collapse: collapse; text-align: center; font-size: 18px; color: " . $data['fontcolor'] . "; }\ntable.inner-table td { border: 1px solid black; padding: 5px; font-weight: bold; }\n.header-cell { font-size: 22px; font-weight: bold; text-align: center; }\n</style>\n</head>\n<body>\n";

    for ($t = 0; $t < $data['table_count']; $t++) {
        if ($data['table_count'] === 1 || $data['table_count'] === 2) {
            $html .= "<div class='table-container'>";
            for ($inner_t = 0; $inner_t < $data['table_count']; $inner_t++) {
                $array = $data['shuffled_content'];
                $html .= "<table class='inner-table'>";
                if ($data['header'] || $data['title']) {
                    $html .= "<tr><td colspan='" . $data['size'] . "' class='header-cell'>" . $data['header'] . "<br>" . $data['title'] . "</td></tr>";
                }
                for ($i = 0; $i < $data['size']; $i++) {
                    $html .= "<tr>";
                    for ($j = 0; $j < $data['size']; $j++) {
                        $index = $i * $data['size'] + $j;
                        $html .= "<td>" . ($array[$index] ?? '') . "</td>";
                    }
                    $html .= "</tr>";
                }
                $html .= "</table>";
            }
            $html .= "</div>";
        } elseif ($data['table_count'] === 4) {
            $html .= "<div class='table-container'>";
            for ($row = 0; $row < 2; $row++) {
                $html .= "<div class='row'>";
                for ($col = 0; $col < 2; $col++) {
                    $array = $data['shuffled_content'];
                    $html .= "<table class='inner-table'>";
                    if ($data['header'] || $data['title']) {
                        $html .= "<tr><td colspan='" . $data['size'] . "' class='header-cell'>" . $data['header'] . "<br>" . $data['title'] . "</td></tr>";
                    }
                    for ($i = 0; $i < $data['size']; $i++) {
                        $html .= "<tr>";
                        for ($j = 0; $j < $data['size']; $j++) {
                            $index = $i * $data['size'] + $j;
                            $html .= "<td>" . ($array[$index] ?? '') . "</td>";
                        }
                        $html .= "</tr>";
                    }
                    $html .= "</table>";
                }
                $html .= "</div>";
            }
            $html .= "</div>";
        }
    }

    $html .= "</body>\n</html>";
    return $html;
}

// Main JSON response logic
if (
    isset($_POST['data']) && 
    isset($_SESSION['requestObject']) && 
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && 
    $_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_SERVER['HTTP_REFERER'])
) {
    $real_url = parse_url($_SERVER['HTTP_REFERER']);
    if ($real_url['host'] == '192.168.1.6') {
        $posted_data = json_decode($_POST['data']); 

        if ($posted_data->uid[0] != '' && $posted_data->uid[0] == $_SESSION['requestObject']) {
            global $global_data;
            $global_data['num_page'] = $posted_data->userInfo->num_pages;
            $global_data['table_count'] = $posted_data->userInfo->total_count;
            $pk = $posted_data->userInfo->pk;

            $sql = "SELECT ts.title, ts.header, ts.html_content, ti.imagename, ti.fontcolor 
                    FROM tbluserselectedtemplates ts 
                    LEFT JOIN tblbgimage ti ON ti.pk = ts.bgimagepk 
                    WHERE ts.pk = '$pk'";  
            $result = mysqli_query($link, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);

                if ($row) {
                    $global_data['title'] = $row['title'];
                    $global_data['header'] = $row['header'];
                    $html_content = $row['html_content'];
                    $parts = explode('|', $html_content);

                    if (count($parts) === 4) {
                        $grid_size = $parts[1];
                        $range = $parts[2];
                        $freesquare = $parts[3];
                        $global_data['size'] = ($grid_size == '33') ? 3 : (($grid_size == '44') ? 4 : 5);
                        $array = explode(",", $parts[0]);
                        shuffle($array);
                        $global_data['shuffled_content'] = $array;
                        $global_data['fontcolor'] = $row['fontcolor'];
                        $global_data['imagename'] = $row['imagename'];

                        $generatedHTML = generateDynamicHTML($global_data);
                        echo json_encode(['success' => true, 'html' => $generatedHTML]);
                    } else {
                        echo json_encode(['error' => "Invalid html_content format."]);
                    }
                } else {
                    echo json_encode(['error' => "No data found for the provided pk."]);
                }
            } else {
                echo json_encode(['error' => "Database query failed: " . mysqli_error($link)]);
            }
        } else {
            echo json_encode(['error' => "Authentication Failed! Invalid UID or session mismatch."]);
        }
    } else {
        echo json_encode(['error' => "Authentication Failed! Invalid referer."]);
    }
} else {
    echo json_encode(['error' => "Invalid request or missing parameters."]);
}
