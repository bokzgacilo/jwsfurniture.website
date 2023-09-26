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

  .is-active {
    background-color: #f7f7f7;
  }

  .is-active > a {
    color: #3c3c3c !important;
  }

  .tab-view > .card {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
  }

  .order {
    display: flex;
    flex-direction: row;
    /* justify-content: space-between; */
  }

  .order > div > img {
    width: 100px;
    height: 100px;
    object-fit: cover;
  }
</style>

<?php
  include('../api/connection.php');
  include('../orm.php');

  $use_orm = new bok_orm();

  $select_orders = $conn -> query($use_orm -> select(['']) -> from('production') -> where("status='Order placed'") -> result());
  $total_orders = $select_orders -> num_rows;

  $select_accepted = $conn -> query($use_orm -> select(['']) -> from('production') -> where("status='ACCEPTED'") -> result());
  $total_accepted = $select_accepted -> num_rows;

  $select_in_delivery = $conn -> query($use_orm -> select(['']) -> from('production') -> where("status='IN DELIVERY'") -> result());
  $total_in_delivery = $select_in_delivery -> num_rows;

  $select_delivered = $conn -> query($use_orm -> select(['']) -> from('production') -> where("status='ORDER COMPLETED'") -> result());
  $total_delivered = $select_delivered -> num_rows;

  $total_ = $total_orders + $total_accepted + $total_in_delivery + $total_delivered;

  $conn -> close();
?>

<div class="offcanvas offcanvas-end" style="width: 40%;" tabindex="-1" id="order-offcanvas" aria-labelledby="order-offcanvasLabel">
  <div class="offcanvas-header">
    <p id='offcanvas-title' class='is-size-4 has-text-weight-bold'>Offcanvas</p>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">

</div>
</div>

<p class="is-size-4 has-text-weight-bold">Total of <?php echo $total_; ?> orders <span class="has-text-weight-normal is-size-7">As of <?php echo date('F j, Y');?></span></p>

<div class="total-number-container">
  <div class="total-number">
    <p class="is-size-2 has-text-weight-semibold"><?php echo $total_orders; ?></p>
    <p class="is-size-7">Orders</p>
  </div>
  <div class="total-number">
    <p class="is-size-2 has-text-weight-semibold"><?php echo $total_accepted; ?></p>
    <p class="is-size-7">Accepted</p>
  </div>
  <div class="total-number">
  <p class="is-size-2 has-text-weight-semibold"><?php echo $total_in_delivery; ?></p>
    <p class="is-size-7">In Delivery</p>
  </div>
  <div class="total-number">
  <p class="is-size-2 has-text-weight-semibold"><?php echo $total_delivered; ?></p>
    <p class="is-size-7">Delivered</p>
  </div>
</div>

<div class="tabs m-0">
  <ul>
    <li name='orders' class="is-active"><a>Orders</a></li>
    <li name='accepted' ><a>Accepted</a></li>
    <li name='in_delivery'><a>In Delivery</a></li>
    <li name='delivered'><a>Delivered</a></li>
  </ul>
</div>

<div class="tab-view">
  <div>Orders</div>
</div>

<script>
  function accept_order(reference_number){
    Swal.fire({
      title: 'Accept Order?',
      text: "Once you accept this order there is no turning back",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Accept order'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'post',
          url: 'api/acceptOrder.php',
          data: {
            reference_number: reference_number
          },
          success: (response) => {
            console.log(response)

            if(response == 1){
              location.reload();
            }
          }
        })
      }
    })
  }

  function deliver_order(reference_number){
    Swal.fire({
      title: 'Deliver Order?',
      text: "Once you accept this order there is no turning back",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Deliver order'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'post',
          url: 'api/deliverOrder.php',
          data: {
            reference_number: reference_number
          },
          success: (response) => {
            console.log(response)

            if(response == 1){
              location.reload();
            }
          }
        })
      }
    })
  }

  function complete_delivery(reference_number){
    Swal.fire({
      title: 'Complete Delivery?',
      text: "Once you accept this order there is no turning back",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Deliver order'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'post',
          url: 'api/completeDelivery.php',
          data: {
            reference_number: reference_number
          },
          success: (response) => {
            console.log(response)

            if(response == 1){
              location.reload();
            }
          }
        })
      }
    })
  }

  function open_offcanvas(reference_number){
    $.ajax({
      type: 'get',
      url: 'api/getTransactionDetail.php',
      data: {
        reference_number: reference_number
      },
      success: (response) => {
        $('#offcanvas-title').text(reference_number)
        
        $('.offcanvas-body').html(response)

        $('#order-offcanvas').offcanvas('show');
      }
    })
  }

  $(document).ready(function(){
    $.ajax({
      type: 'get',
      url: `api/order/orders.php`,
      data:{
        status: 'Order Placed'
      },
      success: (response) => {
        $('.tab-view').html(response)
      }
    })
  })
  
  $('ul > li').on('click', function(){
    var tab_name = $(this).attr('name');
    
    $('ul > li').removeClass('is-active')

    $(this).addClass('is-active')
    
    $.ajax({
      type: 'get',
      url: `api/order/${tab_name}.php`,
      data:{
        status: tab_name
      },
      success: (response) => {
        $('.tab-view').html(response)
      }
    })
  })
</script>