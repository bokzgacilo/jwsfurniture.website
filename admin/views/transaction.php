<style>
  .charts-container {
    display: grid;
    grid-template-columns: 49% 50%;
    column-gap: 10px;
  }
  
  .total-earnings {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
  }

  .earning {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .rec-trans {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }

  .recent-transactions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
</style>

<?php
  include('../api/connection.php');

  $transaction = $conn -> query("SELECT * FROM transactions WHERE status='SUCCEEDED'");
  $total_transactions = $transaction -> num_rows;

  $mode = $conn -> query("SELECT mode FROM transactions WHERE status='SUCCEEDED'");

  $COD = 0;
  $GCASH = 0;
  $PAYPAL = 0;
  $MAYA = 0;

  while($row = $mode -> fetch_array()){
    switch($row['mode']){
      case 'COD':
        $COD += 1;
        break;
      case 'GCASH':
        $GCASH += 1;
        break;
      case 'PAYPAL':
        $PAYPAL += 1;
        break;
      case 'MAYA':
        $MAYA += 1;
        break;
    }
  }


  $total_earnings = $conn -> query("SELECT SUM(amount) FROM transactions WHERE status='SUCCEEDED'");

  $total_earnings = $total_earnings -> fetch_assoc();
  $total_earnings = number_format($total_earnings['SUM(amount)'], 2, '.', ',');

  $recent_transactions = $conn -> query("SELECT * FROM transactions WHERE status='SUCCEEDED' ORDER BY date_created DESC LIMIT 7 ");
?>

<div style="width: 50%;" class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="productViewCanvas" aria-labelledby="productViewCanvasLabel">
  <div class=" offcanvas-header">
    <h5 class="offcanvas-title" id="productViewCanvasLabel">Backdrop with scrolling</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="w-50 offcanvas-body">
    <p>Try scrolling the rest of the page to see this option in action.</p>
  </div>
</div>

<p class="is-size-4 has-text-weight-bold">Transaction and Sales</p>
<div class="total-earnings">
  <div class="earning">
    <p class="is-size-2 has-text-weight-bold">₱ <?php echo $total_earnings;?></p>
    <p class='is-size-7'>Total Earnings</p>
  </div>
  <div class="earning">
    <p class="is-size-2 has-text-weight-bold"><?php echo $total_transactions;?></p>
    <p class='is-size-7'>Total Transactions Made</p>
  </div>
</div>
<div class="charts-container">
  <div>
    <canvas id="by-mode-of-payment">

    </canvas>
  </div>
  <div>
    <p class="is-size-5 has-text-weight-semibold">Recent Transactions</p>
    <div class="recent-transactions mt-2  ">
      <?php
        while($trans = $recent_transactions -> fetch_array()){
          echo "
            <div class='card p-2 rec-trans'>
              <div>
                <a class='is-size-6' >".$trans['transaction_id']."</a>
                <p class='is-size-7'>".date("F j, Y g:i a", strtotime($trans['date_created']))."</p>
              </div>
              <span class='tag is-success is-small'>+".$trans['amount']."</span>
            </div>
          ";
        }
      ?>
    </div>
  </div>
</div>

<script>
  function getAllTransactions(){
    $.ajax({
      type: 'get',
      url: 'api/getAllTransactions.php',
      success: (response) => {
        $('.t-body').html(response)
      }
    })
  }

  function viewTransaction(id){
    $('#productViewCanvas').offcanvas('toggle');
  }
  
  $(document).ready(function(){
    getAllTransactions()
  })
</script>

<script>
// Get the canvas element
var ctx = document.getElementById('by-mode-of-payment').getContext('2d');

// Create the chart
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['COD', 'GCash', 'PayPal', 'Maya'],
      datasets: [{
        label: 'Number of Transactions',
        data: [<?php echo "$COD, $GCASH, $PAYPAL, $MAYA"; ?>],
        backgroundColor: [
          'rgba(0, 0, 0, 0.5)',
          'rgba(0, 0, 255, 0.5)',
          'rgba(219, 190, 2, 0.5)',
          'rgba(5, 107, 29, 0.5)'
        ],
        
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
});
</script>

<?php
  $conn -> close();
?>