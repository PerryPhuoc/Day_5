<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demo Shop</title>
  <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
  <link href="/Day5/assets/frontend/css/style.css" type="text/css" rel="stylesheet" />
  <style>
    .image {
      width: 100px;
      height: 100px;
    }
  </style>
</head>
<body class="d-flex flex-column h-100">
  <!-- Header -->
  <?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

  <main role="main" class="mb-2">
    <?php
    include_once(__DIR__ . '/../../dbconnect.php');
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    ?>
    
    <div class="container mt-4">
      <!-- Alert thông báo -->
      <div id="alert-container" class="alert alert-warning alert-dismissible fade d-none" role="alert">
        <div id="message">&nbsp;</div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <h1 class="text-center">Cart</h1>

      <div class="row">
        <div class="col col-md-12">
          <?php if (!empty($cart)) : ?>
            <table id="tblCart" class="table table-bordered">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Sub Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="datarow">
                <?php $no = 1; ?>
                <?php foreach ($cart as $item) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <?php if (empty($item['image'])) : ?>
                        <img src="/Day5/assets/shared/img/default-image_600.png" class="img-fluid image" />
                      <?php else : ?>
                        <img src="/Day5/assets/<?= $item['image'] ?>" class="img-fluid image" />
                      <?php endif; ?>
                    </td>
                    <td><?= $item['name'] ?></td>
                    <td>
                      <input type="number" class="form-control" id="quantity_<?= $item['id'] ?>" name="quantity" value="<?= $item['quantity'] ?>" />
                      <button class="btn btn-primary btn-sm btn-update-quantity" data-id="<?= $item['id'] ?>">Update</button>
                    </td>
                    <td><?= number_format($item['price'], 2, ".", ",") ?> vnđ</td>
                    <td><?= number_format($item['quantity'] * $item['price'], 2, ".", ",") ?> vnđ</td>
                    <td>
                      <a id="delete_<?= $no ?>" data-id="<?= $item['id'] ?>" class="btn btn-danger btn-delete-product">
                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else : ?>
            <h2>Cart Empty</h2>
          <?php endif; ?>

          <a href="/Day5/frontend" class="btn btn-warning btn-md">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Continue Shopping
          </a>
          <a href="/Day5/frontend/pages/checkout.php" class="btn btn-primary btn-md">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Checkout
          </a>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>

  <!-- Scripts -->
  <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>

  <!-- JavaScript riêng -->
  <script>
    $(document).ready(function() {
      function removeProductItem(id) {
        $.ajax({
          url: '/Day5/frontend/api/removeCartItem.php',
          method: "POST",
          dataType: 'json',
          data: { id: id },
          success: function(data) {
            location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            $('#message').html(`<h1>Can not delete item</h1>`);
            $('.alert').removeClass('d-none').addClass('show');
          }
        });
      }

      $('#tblCart').on('click', '.btn-delete-product', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        removeProductItem(id);
      });

      function updateCartItem(id, quantity) {
        $.ajax({
          url: '/Day5/frontend/api/updateCartItem.php',
          method: "POST",
          dataType: 'json',
          data: { id: id, quantity: quantity },
          success: function(data) {
            location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            $('#message').html(`<h1>Can not update item</h1>`);
            $('.alert').removeClass('d-none').addClass('show');
          }
        });
      }

      $('#tblCart').on('click', '.btn-update-quantity', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        var quantity = $('#quantity_' + id).val();
        updateCartItem(id, quantity);
      });
    });
  </script>
</body>
</html>
