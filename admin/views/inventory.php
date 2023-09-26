<?php
  include('../api/connection.php');

  $get_stock = $conn -> query("SELECT SUM(stock) as total_stock FROM product");
  $get_stock = $get_stock -> fetch_assoc();
  $get_sold = $conn -> query("SELECT SUM(sold) as total_sold FROM product");
  $get_sold = $get_sold -> fetch_assoc();

?>
<script src="js/bulma.modal.js"></script>
<style>
  .total-number-container {
    display: grid;
    grid-template-columns: 20% 20% 20% 20%;
    justify-content: center;
  }

  .total-number {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
</style>

<div id="restock-modal" class="modal">
  <div class="modal-background"></div>

  <div class="modal-content">
    <div class="stock-box box p-4">
      <p>Tesing</p>
    </div>
  </div>

  <button class="modal-close is-large" onclick="closeModal()"></button>
</div>

<p class="is-size-4 has-text-weight-bold">Inventory</p>
<div class="total-number-container">
  <div class="total-number">
    <p class="is-size-2 has-text-weight-semibold"><?php echo $get_stock['total_stock']; ?></p>
    <p class="is-size-7">Total number of products in inventory</p>
  </div>
  <div class="total-number">
    <p class="is-size-2 has-text-weight-semibold"><?php echo $get_sold['total_sold']; ?></p>
    <p class="is-size-7">Total number of products in inventory</p>
  </div>
</div>
<table class="table">
  <thead>
    <tr>
      <th>Product Name</th>
      <th>Price</th>
      <th>Stock</th>
      <th>Sold</th>
      <th>Reviews</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php

      $select = $conn -> query("SELECT * FROM product");

      while($row = $select -> fetch_array()){
        $count = 0;
        
        if($row['review'] != "none"){
          $count = count(json_decode($row['review'], true));
        }

        echo "
          <tr>
            <td>".$row['name']."</td>
            <td>₱ ".number_format($row['price'], 2, '.', ',')."</td>
            <td>".$row['stock']."</td>
            <td>".$row['sold']."</td>
            <td>$count</td>
            <td>
              <button id='".$row['id']."' onclick='openRestockModal(this.id);' class='button is-small'>Re-stock</button>
            </td>
          </tr>
        ";
      }
      
      $conn -> close();
    ?>
  </tbody>
</table>

<script>
  function openRestockModal(product_id){
    
    $.ajax({
      type: 'get',
      url: 'api/getProductStock.php',
      data: {
        id: product_id
      },
      success: response => {
        $('.stock-box').html(response)
        $('#restock-modal').addClass('is-active');
      }
    })
  }

  function closeModal(){
    $('#restock-modal').removeClass('is-active');
  }

  $(document).on('click', '.modal-background', function(){
    $('#restock-modal').removeClass('is-active');
  })
</script>