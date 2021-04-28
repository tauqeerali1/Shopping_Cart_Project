 <?php
 session_start();
 $connect = mysqli_connect("localhost", "root", "9636463361", "test");
 if(isset($_POST["add_to_cart"]))
 {
      if(isset($_SESSION["shopping_cart"]))
      {
           $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
           if(!in_array($_GET["id"], $item_array_id))
           {
                $count = count($_SESSION["shopping_cart"]);
                $item_array = array(
                     'item_id'               =>     $_GET["id"],
                     'item_name'               =>     $_POST["hidden_name"],
                     'item_price'          =>     $_POST["amount"],
                     'item_quantity'          =>     $_POST["quantity"]
                );
                $_SESSION["shopping_cart"][$count] = $item_array;
           }
           else
           {
                echo '<script>alert("Item Already Added")</script>';
                echo '<script>window.location="cart.php"</script>';
           }
      }
      else
      {
           $item_array = array(
                'item_id'               =>     $_GET["id"],
                'item_name'               =>     $_POST["hidden_name"],
                'item_price'          =>     $_POST["amount"],
                'item_quantity'          =>     $_POST["quantity"]
           );
           $_SESSION["shopping_cart"][0] = $item_array;
      }
 }
 if(isset($_GET["action"]))
 {
      if($_GET["action"] == "delete")
      {
           foreach($_SESSION["shopping_cart"] as $keys => $values)
           {
                if($values["item_id"] == $_GET["id"])
                {
                     unset($_SESSION["shopping_cart"][$keys]);
                     echo '<script>alert("Item Removed")</script>';
                     echo '<script>window.location="cart.php"</script>';
                }
           }
      }
 }
 ?>

 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>GCCSRM | PROJECT</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      </head> 
      <style>
            table,tr,th,td
            {
                border: 1px solid black;

            }
            table{
                display: auto;
            }
            .split1 {
              float: right;
              width: 30%;
              margin-top: 50px;
            }
            .split2 {
              float: left;
              width: 50%;
            }
                        
        </style> 
      <body>  
        <section class="split1">
          <form action="cart.php" method="post">
            <input type="text" name="q" placeholder="Search Product Here...">
            <select name="column">
              <option value="">Select Filter</option>
              <option value="name">Name</option>
              <option value="price">price</option>
              <option value="product">product</option>
          </select>
      <input type="submit" name="search" value="Search">
      <p>Search by <span style="color: red;">Product Company, Product Name & Price</span></p><br><br>
                              

<?php
  if (isset($_POST['search'])) {
    $connect = new mysqli("localhost", "root", "9636463361", "test");
    $q = $connect->real_escape_string($_POST['q']);
    $column = $connect->real_escape_string($_POST['column']);
}
    if ($column == "" || ($column != "name" && $column != "price" && $column != "product")){
      $column = "name";
    } ?>

    <table>
                <tr>
                    <th>Photo</th>
                    <th>Product Name</th>
                    <th>Price</th>
                </tr>

    <?php $sql = $connect->query("SELECT * FROM tbl_product WHERE $column LIKE '%$q%'");
    if ($sql->num_rows > 0) 
      while ($row = $sql->fetch_array()): ?>
        
          <tr>
                    <td><?php echo "<a href='$row[image]'>"; ?><img src="<?php echo $row['image']; ?>" height="100px;"></td>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['price'];?></td>
                </tr>
    
        <?php endwhile;?>
      </table>
          </form>
        </section>
  
<br />
           <section class="split2">
            <div class="container" style="width:700px;">
                <h3 align="center">All Product Are Here</h3><br />
                <?php
                $query = "SELECT * FROM tbl_product ORDER BY id ASC";
                $result = mysqli_query($connect, $query);
                if(mysqli_num_rows($result) > 0)
                {
                     while($row = mysqli_fetch_array($result))
                     {
                ?>
                <div class="col-md-4">
                     <form method="post" action="cart.php?action=add&id=<?php echo $row["id"]; ?>">
                          <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">
                               <img src="<?php echo $row["image"]; ?>" class="img-responsive" /><br />
                               <h4 class="text-info"><?php echo $row["name"]; ?></h4>
                               <h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>
                               <input type="text" name="quantity" class="form-control" value="1" />
                               <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />
                               <input type="hidden" name="amount" value="<?php echo $row["price"]; ?>" />
                               <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />
                          </div>
                     </form>
                </div>
                <?php
                     }
                }
                ?>
                <div style="clear:both"></div>
                <br />
                <h3>YOUR CART IS HERE <span style="font-size:40px;">&#8595;</span></h3>
                <div class="table-responsive">
                     <table class="table table-bordered">
                          <tr>
                               <th width="40%">Product Name</th>
                               <th width="10%">Quantity</th>
                               <th width="20%">Price</th>
                               <th width="15%">Total</th>
                               <th width="5%">Action</th>
                          </tr>
                          <?php
                          if(!empty($_SESSION["shopping_cart"]))
                          {
                               $total = 0;
                               foreach($_SESSION["shopping_cart"] as $keys => $values)
                               {
                          ?>
                          <tr>
                               <td><?php echo $values["item_name"]; ?></td>
                               <td><?php echo $values["item_quantity"]; ?></td>
                               <td>$ <?php echo $values["item_price"]; ?></td>
                               <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                               <td><a href="cart.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
                          </tr>
                          <?php
                                    $total = $total + ($values["item_quantity"] * $values["item_price"]);
                               }
                          ?>
                          <tr>
                               <td colspan="3" align="right">Total</td>
                               <td align="right"><b>$ <?php echo number_format($total, 2); ?></b></td>
                               <td></td>
                          </tr>
                          <?php
                          }
                          ?>
                     </table>
                </div>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                        <input type="text" name="amount" class="form-control" placeholder="Total Amount" value="Total Amount = $ <?php echo $total; ?>" />
                        <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />
                                        <input type="hidden" name="cmd" value="_s-xclick">
                                        <input type="hidden" name="hosted_button_id" value="W58SLJE854UH6">
                                        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!" style="width: 250px;">
                                        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="5" height="5">
                                </form>
           </div><br /><br /><br />
           </section>
  
      </body>  
 </html>
