<?php
include('../../config/config.php');
if (isset($_GET['data'])) {
    $data = $_GET['data'];
} else {
    $data = '';
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    $product_id = "";
}
$product_ids = json_decode($data);
$product_name = $_POST['product_name'];
$product_brand = $_POST['product_brand'];
$product_summary = $_POST['product_summary'];
$product_scent = $_POST['product_scent'];
$product_category = $_POST['product_category'];
$product_price_import = $_POST['product_price_import'];
$product_price = $_POST['product_price'];
$product_sale = $_POST['product_sale'];
$product_description = $_POST['product_description'];
$product_image = $_FILES['product_image']['name'];
$product_image_tmp = $_FILES['product_image']['tmp_name'];
$product_image = time() . '_' . $product_image;
$product_status = $_POST['product_status'];

print_r($product_scent);

//Them san pham
if (isset($_POST['product_add'])) {
    $sql_add = "INSERT INTO product(product_name, product_category, product_summary, product_brand, product_price_import, product_price, product_sale, product_description, product_image, product_status) VALUE('" . $product_name . "', '" . $product_category . "', '" . $product_summary . "',  '" . $product_brand . "', '" . $product_price_import . "', '" . $product_price . "', '" . $product_sale . "', '" . $product_description . "', '" . $product_image . "', '" . $product_status . "')";
    mysqli_query($mysqli, $sql_add);
    move_uploaded_file($product_image_tmp, 'uploads/' . $product_image);
    $insertedId = mysqli_insert_id($mysqli);
    foreach ($product_scent as $variant_id) {
        $sql_get_scent_name = "SELECT * FROM scent WHERE scent_id = '".$variant_id."' LIMIT 1";
        $query_get_scent_name = mysqli_query($mysqli, $sql_get_scent_name);
        $scent_name = mysqli_fetch_array($query_get_scent_name);
        $sql_insert_variant = "INSERT INTO product_variant(product_id, scent_id, variant_name) VALUE('".$insertedId."', '".$variant_id."', '".$scent_name['scent_name']."');";
        mysqli_query($mysqli, $sql_insert_variant);
    }
    
    header('Location: ../../index.php?action=product&query=product_list&message=success');

// Sua
} elseif (isset($_POST['product_edit'])) {
    if ($_FILES['product_image']['name'] != '') {
        move_uploaded_file($product_image_tmp, 'uploads/' . $product_image);
        $sql = "SELECT * FROM product WHERE product_id = '$product_id' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        while ($row = mysqli_fetch_array($query)) {
            unlink('uploads/' . $row['product_image']);
        }
        $sql_update = "UPDATE product SET product_name = '" . $product_name . "', product_brand = '" . $product_brand . "', product_summary = '". $product_summary ."', product_category = '" . $product_category . "', product_price_import = '" . $product_price_import . "', product_price = '" . $product_price . "', product_sale = '" . $product_sale . "', product_description = '" . $product_description . "', product_image = '" . $product_image . "', product_status = '" . $product_status . "' WHERE product_id = '" . $product_id . "'";
    } else {
        $sql_update = "UPDATE product SET product_name = '" . $product_name . "', product_brand = '" . $product_brand . "', product_summary = '". $product_summary ."',  product_category = '" . $product_category . "', product_price_import = '" . $product_price_import . "', product_price = '" . $product_price . "', product_sale = '" . $product_sale . "', product_description = '" . $product_description . "', product_status = '" . $product_status . "' WHERE product_id = '" . $product_id . "'";
    }
    mysqli_query($mysqli, $sql_update);


// Xoa trong bang n-n
    $sql_delete_variant = "DELETE FROM product_variant WHERE product_id = '$product_id'";
    mysqli_query($mysqli, $sql_delete_variant);

    foreach ($product_scent as $variant_id) {
        $sql_get_scent_name = "SELECT * FROM scent WHERE scent_id = '".$variant_id."' LIMIT 1";
        $query_get_scent_name = mysqli_query($mysqli, $sql_get_scent_name);
        $arr_scent = mysqli_fetch_array($query_get_scent_name);
        print_r ($arr_scent['scent_name']);
        $sql_insert_variant = "INSERT INTO product_variant(product_id, scent_id, variant_name) VALUE('".$product_id."', '".$variant_id."', '".$arr_scent['scent_name']."');";
        mysqli_query($mysqli, $sql_insert_variant);
    }

    header('Location: ../../index.php?action=product&query=product_list&message=success');
} elseif (isset($_GET['product_sale'])) {
    $sale = $_GET['product_sale'];
    foreach ($product_ids as $id) {
        $sql_sale = "UPDATE product SET product_sale = $sale WHERE product_id = '" . $id . "'";
        mysqli_query($mysqli, $sql_sale);
    }
    header('Location: ../../index.php?action=product&query=product_list&message=success');
} elseif (isset($_GET['deleteevaluate']) && $_GET['deleteevaluate'] == 1) {
    $evaluate_ids = json_decode($data);
    foreach ($evaluate_ids as $id) {
        $sql_delete_evaluate = "DELETE FROM evaluate WHERE evaluate_id = '" . $id . "'";
        mysqli_query($mysqli, $sql_delete_evaluate);
    }
    header('Location: ../../index.php?action=product&query=product_edit&product_id='.$product_id.'&message=success#product_evaluate');
} elseif (isset($_GET['spamevaluate']) && $_GET['spamevaluate'] == 1) {
    $evaluate_ids = json_decode($data);
    foreach ($evaluate_ids as $id) {
        $sql_update_evaluate = "UPDATE evaluate SET evaluate_status = -1 WHERE evaluate_id = '" . $id . "'";
        mysqli_query($mysqli, $sql_update_evaluate);
    }
    header('Location: ../../index.php?action=product&query=product_edit&product_id='.$product_id.'&message=success#product_evaluate');
} else {
    foreach ($product_ids as $id) {
        $sql = "SELECT * FROM product WHERE product_id = '$id' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        while ($row = mysqli_fetch_array($query)) {
            unlink('uploads/' . $row['product_image']);
        }
        $sql_delete = "DELETE FROM product WHERE product_id = '" . $id . "'";
        mysqli_query($mysqli, $sql_delete);

        $sql_delete_variant = "DELETE FROM product_variant WHERE product_id = '$id'";
        mysqli_query($mysqli, $sql_delete_variant);
    }
    header('Location: ../../index.php?action=product&query=product_list&message=success');
}
?>