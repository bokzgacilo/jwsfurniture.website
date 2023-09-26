<?php
  include('connection.php');

  $productName = $_POST['name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  
  if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["image"]["tmp_name"];

    $name = $_FILES["image"]["name"];
    $filepath = "../../uploads/products/" . $name;
    
    $cleanedTextareaValue = htmlspecialchars(trim($description));
    
    if(move_uploaded_file($tmp_name, $filepath)){
      $sql = "INSERT INTO product(name, description, price, category, product_photo) VALUES(
        '$productName',
        '$cleanedTextareaValue',
        $price,
        '$category',
        '../uploads/products/$name'
      )";

      $result = $conn -> query($sql);

      if($result){
        header('location: https://jwsfurniture.website/admin/dashboard.php#products');
      }
    }
  } else {
    echo "Error uploading file.";
  }

  $conn -> close();
?>