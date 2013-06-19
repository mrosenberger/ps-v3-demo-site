<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      hr {
        color: #000000;
        background-color: #000000;
        height: 2px;
      }
    </style>
    <?php require 'render-product.php' ?>
    <?php require 'ps-v3-library.php' ?>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
      .sidebar {
	margin-left: 0;
	padding-right: 19px;
	border-right: 1px groove #000000;
	min-height: 768px;
      }
    </style>
    <title>ShopFoo</title>
    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
	<div class="container">
	  <a class="brand">ShopFoo</a>
	  <div class="nav-collapse collapse">
	    <ul class="nav">
	      <li><a href="c">Departments</a></li>
	      <li><a href="s">Stores</a></li>
	      <li><a href="coupons/1">Coupons</a></li>
	      <li><a href="about">About</a></li>
	    </ul>
	    <form class="navbar-form pull-right" method="get" action="search.php">
	      <input name="keyword" class="span2" type="text" placeholder="Search products...">
	      <select name="category" class="span3" id="category"> 
		<option value="" id="nav-cat-select-default">All products</option>
		<option value="32194" id="nav-cat-select-32194">Arts &amp; Crafts</option>
		<option value="2000" id="nav-cat-select-2000">Automotive Parts &amp; Vehicles</option>
		<option value="15000" id="nav-cat-select-15000">Baby &amp; Family</option>
		<option value="3000" id="nav-cat-select-3000">Clothing &amp; Accessories</option>
		<option value="5000" id="nav-cat-select-5000">Computers &amp; Software</option>
		<option value="7000" id="nav-cat-select-7000">Electronics</option>
		<option value="10000" id="nav-cat-select-10000">Events &amp; Tickets</option>
		<option value="12000" id="nav-cat-select-12000">Food, Flowers &amp; Gifts</option>
		<option value="13000" id="nav-cat-select-13000">Health &amp; Beauty</option>
		<option value="16000" id="nav-cat-select-16000">Home &amp; Garden</option>
		<option value="9000" id="nav-cat-select-9000">Mature &amp; Adult</option>
		<option value="21000" id="nav-cat-select-21000">Media</option>
		<option value="22000" id="nav-cat-select-22000">Musical Instruments</option>
		<option value="24000" id="nav-cat-select-24000">Office &amp; Professional Supplies</option>
		<option value="23000" id="nav-cat-select-23000">Pets &amp; Animal Supplies</option>
		<option value="25000" id="nav-cat-select-25000">Shoes &amp; Accessories</option>
		<option value="32346" id="nav-cat-select-32346">Specialty &amp; Novelty</option>
		<option value="27000" id="nav-cat-select-27000">Sports &amp; Outdoor Activities</option>
		<option value="31000" id="nav-cat-select-31000">Toys, Games &amp; Hobbies</option>
		<option value="32345" id="nav-cat-select-32345">Travel</option>
		<option value="8400" id="nav-cat-select-8400">Video Games, Consoles &amp; Accessories</option>
		<option value="9100" id="nav-cat-select-9100">Weapons</option>
		<?php
		  if(array_key_exists("category", $_GET)) {
		    $val = $_GET["category"];
		    print('<script language="javascript">document.getElementById("nav-cat-select-".concat(' . $val . ')).setAttribute("selected", "true")</script>' . "\n");
		  }
		?>
	      </select>
	      <button type="submit" class="btn">Search</button>
	    </form>
	  </div>
	</div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="span2 sidebar">
          Categories:
          <ul>
	    <li>Something</li>
	    <li>Something</li>
	    <li>Something</li>
	    <li>Something</li>
	  </ul>
        </div>
        <div class="span10">
        <?php
        $api = new PsApiCall(array(
				   'account' => 'd1lg0my9c6y3j5iv5vkc6ayrd',
				   'catalog' => 'dp4rtmme6tbhugpv6i59yiqmr',
				   'logging' => false
				   ));
        $valid_options = array('category', 'include_discounts', 'keyword', 'keyword_description', 'keyword_ean', 'keyword_isbn', 'keyword_mpn', 'keyword_name', 'keyword_person', 'keyword_upc', 'keyword_sku', 'merchant', 'merchant_type', 'page', 'percent_off', 'percent_off_max', 'percent_off_min', 'postal_code', 'price', 'price_max', 'product', 'product_spec', 'include_identifiers', 'results_per_page', 'session', 'tracking_id');
        $parameters = array();
        foreach ($_GET as $key=>$value) {
	  if (in_array($key, $valid_options)) {
	    $parameters[$key] = $value;
	  }
	}
        $api->get('products', $parameters);
        foreach ($api->resource('products') as $product) {
	  renderProduct($product);
	}
      ?>
      </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
