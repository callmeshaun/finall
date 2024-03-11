
<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  

      
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />


</head>
<body>
  <header>

      <header class="header">
        <div class="logoContent">
            <a href="#" class="logo"><img src="images/logo.png" alt=""></a>
            <h1 class="logoName">Cakes_Zone</h1>
        </div>

      
        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="#cutomized">Customized Cakes</a>
            <a href="#reviews">Reviews</a>
            <a href="#blogs">Blogs</a>
        </nav>

        <div class="group">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24">
                <g>
                    <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                </g>
            </svg>
            <input placeholder="Search" type="search" class="input">
        </div>


       

        <div class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
</div>
       

        <div class="dropdown">
            <button class="dropbtn">Account
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
              <a href="login.html">login</a>
              <a href="registration.php">Register</a>
              <a href="logout.php">logout</a>

            </div> 




  </header>

<!-- home back -->

<section class="home" id="home">
      <div class="homeContent">
          <h2>Fresh and Delicious  </h2>
          <p>we serve cakes of different categories</p>
          <div class="home-btn">
              <a href="#"><button>see more</button></a>
          </div>
      </div>
  </section>

  <!--ends here -->

  <div class="Cakes">

      
            
           <h1>Cakes</h1>

           <br>

            
 
      </div>


     <div class="container">
  <div class="food-container">
    <?php
    include 'database.php';
    $stmt = $conn->prepare('SELECT * FROM product');
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()):
    ?>
    <div class="card">
      <img src="<?= $row['product_image'] ?>" class="card-img-top" alt="<?= $row['product_name'] ?>">
      <div class="card-body">
        <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4>
        <h5 class="card-text text-center text-danger"><i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2) ?>/-</h5>
        <form action="" class="form-submit">
          <div class="quantity">
            <div class="inc">
              <b>Quantity : </b>
            </div>
            <div class="prod">
              <input type="number" class="form-control pqty" value="<?= $row['product_qty'] ?>">
            </div>
          </div>
          <input type="hidden" class="pid" value="<?= $row['id'] ?>">
          <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
          <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
          <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
          <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
          <button class="btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add to cart</button>
        </form>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>


  <!-- Displaying Products End -->

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Send product details in the server
    $(".addItemBtn").click(function(e) {
      e.preventDefault();
      var $form = $(this).closest(".form-submit");
      var pid = $form.find(".pid").val();
      var pname = $form.find(".pname").val();
      var pprice = $form.find(".pprice").val();
      var pimage = $form.find(".pimage").val();
      var pcode = $form.find(".pcode").val();

      var pqty = $form.find(".pqty").val();

      $.ajax({
        url: 'action.php',
        method: 'post',
        data: {
          pid: pid,
          pname: pname,
          pprice: pprice,
          pqty: pqty,
          pimage: pimage,
          pcode: pcode
        },
        success: function(response) {
          $("#message").html(response);
          window.scrollTo(0, 0);
          load_cart_item_number();
        }
      });
    });

    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();

    function load_cart_item_number() {
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {
          cartItem: "cart_item"
        },
        success: function(response) {
          $("#cart-item").html(response);
        }
      });
    }
  });
  </script>


<footer class="footer" id="contact">
  <div class="box-container">
      <div class="mainBox">
          <div class="content">
              <a href="#"><img src="images/logo.png" alt=""></a>
              <h1 class="logoName">  Cakes Zone </h1>
          </div>

          <p>Every slice is addictive.</p>

      </div>
      <div class="box4">
          <h3>Quick link</h3>
          <a href="#"> <i class="fas fa-arrow-right"></i>Home</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>Customized Cakes</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>Categories</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>Reviews</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>Blogs</a>

      </div>
      <div class="box4">
          <h3>Extra link</h3>
          <a href="#"> <i class="fas fa-arrow-right"></i>Account info</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>order item</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>privacy policy</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>payment method</a>
          <a href="#"> <i class="fas fa-arrow-right"></i>our  services</a>
      </div>
      <div class="box4">
          <h3>Contact Info</h3>
          <a href="#"> <i class="fas fa-phone"></i>+8390304748</a>
          <a href="#"> <i class="fas fa-envelope"></i>cakeszone@gmail.com</a>

      </div>

  </div>
  <div class="share">
      <a href="#" class="fab fa-facebook-f"></a>
      <a href="#" class="fab fa-twitter"></a>
      <a href="#" class="fab fa-instagram"></a>
      <a href="#" class="fab fa-linkedin"></a>
      <a href="#" class="fab fa-pinterest"></a>
  </div>
  <div class="credit">
     </b>  <span> MADE WITH LOVE BY SHAUNAK & PRANJAL</span>
  </div>
</footer>

</body>

</html>


