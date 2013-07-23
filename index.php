<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php");?>
    <title>ShopFoo</title>
  </head>
  <body>
    <?php require("navbar.php");?>
    <div class="container">
      <div class="row">
        <div class="span2 sidebar">
          <h3>Categories</h3>
	  <hr>
	  <h6>Focus search to:</h6>
	  <ul>
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
	    foreach($api->resource('categories') as $category) {
	      println('<li><a href="search.php?keyword=' . $_GET['keyword'] . '&category=' . $category->attr('id') . '">');
	      println($category->attr('name'));
	      println('</a></li>');
	    }
	  ?>
	  </ul>
        </div>
        <div class="span10">
	  <?php
	    if (array_key_exists("keyword", $_GET) and $_GET['keyword'] != '') {
	      println('<h2>Results for "' . $_GET['keyword'] . '"</h2>');
	      println('<hr>');
	    }
	    foreach ($api->resource('products') as $product) {
	      renderProduct($product);
	    }
	  ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
