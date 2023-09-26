<style>

</style>

<div class="offcanvas w-25 offcanvas-end" tabindex="-1" id="user" aria-labelledby="userLabel">
  <div class="offcanvas-body is-flex is-flex-direction-column">
    
  </div>
</div>

<p class='is-size-4 has-text-weight-bold'>User Accounts</p>

<table class="table">
  <thead>
    <tr>
      <th>Image</th>
      <th>Name</th>
      <th>Email</th>
      <th>Password</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      include('../api/connection.php');

      $select = $conn -> query("SELECT * FROM user");

      while($row = $select -> fetch_array()){
        $photo_url = '';
        if (filter_var($row['photo_url'], FILTER_VALIDATE_URL) !== false) {
          $photo_url = $row['photo_url'];
        } else {
          $photo_url = 'https://jwsfurniture.website/client2/' . $row['photo_url'];
        }
        echo "
          <tr>
            <td>
              <figure class='image is-48x48'>
                <img style='object-fit:cover; height:48px;' class='is-rounded' src='$photo_url' />
              </figure>
            </td>
            <td class='is-size-6 has-text-weight-bold'>
              <a>".$row['name']."</a>
            </td>
            <td>".$row['email']."</td>
            <td>".$row['password']."</td>
            <td>
              <a onclick='openUserDetail(this.id)' class='button is-link' id='".$row['uid']."' data-bs-toggle='offcanvas' href='#user' role='button' aria-controls='user' title='User Detail'>Open User Detail</a>
            </td>
          </tr>
        ";
      }
      
      $conn -> close();
    ?>
  </tbody>
</table>

<script>
  function openUserDetail(user_id){
    $.ajax({
      type: 'get',
      url: 'api/getUserDetail.php',
      data: {
        uid : user_id
      },
      success:response => {
        $('#user > .offcanvas-body').html(response)
        $(user_id).trigger('click');
      }
    })
  }
</script>