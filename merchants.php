<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php");?>
    <?php require("render-merchant.php");?>
    <title>ShopFoo Merchants</title>
  </head>
  <body>
    <?php require("navbar.php");?>
    <div class="container">
      <div class="row">
        <div class="span2 sidebar">
	  <h3>Categories</h3>
	  <hr>
	  <ul>
	  <?php
	    $alpha = 'a';
	    if (array_key_exists('alpha', $_GET)) {
	      $alpha = $_GET['alpha'];
	    }
	    $page = 1;
	    if (array_key_exists('page', $_GET)) {
	      $page = $_GET['page'];
	    }
	    $api = new PsApiCall(array(
				   'account' => 'd1lg0my9c6y3j5iv5vkc6ayrd',
				   'catalog' => 'dp4rtmme6tbhugpv6i59yiqmr',
				   'logging' => false
				   ));
	    $api->get('merchants', array('results_per_page' => 100,
					 'page' => $page,
					 'alpha' => ord($alpha) - 64));
	    foreach($api->resource('categories') as $category) {
	      println('<li><a href="">');
	      println($category->attr('name'));
	      println('</a></li>');
	    }
	  ?>
	  </ul>
        </div>
        <div class="span10">
	  <div class="offset2"><h1>Stores</h1></div>
	  <form name="alphachoose" method="get" action="merchants.php" style="float:right">
	     <select onchange="this.form.submit()" name="alpha" class="span1" id="alpha">
	      <?php
	        foreach(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $letter) {
		  println('<option value=' . $letter . '> ' . $letter . '</option>');
		}
	      ?>

	     </select>
	  </form>
	  <hr>
	  <div class="row">
	    <div class="span2 offset2">
	      <h4>Name</h4>
	    </div>
	    <div class="span2">
	      <h4>Coupons</h4>
	    </div>
	    <div class="span2">
	      <h4>Products</h4>
	    </div>
	  </div>
	  <?php
	    
	    foreach ($api->resource('merchants') as $merchant) {
	      //println($merchant->attr('name') . '<br>');
	      render_merchant($merchant);
	    }
	    ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
