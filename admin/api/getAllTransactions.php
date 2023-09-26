<?php
  include('connection.php');

  $sql = $conn -> query("SELECT * FROM transactions");

  while($row = $sql -> fetch_array()){
    echo "
    <div class='t-data'>
      <a onclick='viewTransaction(this.id)' id='".$row['id']."' class='col-4'>".$row['transaction_id']."</a>
      <p class='col-2'>".$row['reference_number']."</p>
      <p class='col-1'>".$row['amount']."</p>
      <p class='col-2'>".$row['status']."</p>
      <p class='col'>".$row['date_created']."</p>
    </div>";
  }

  $conn -> close();
?>