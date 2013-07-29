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
	    $api = new PsApiCall($api_key, $catalog_key);
	    $api->get('products');
	    foreach($api->getCategories() as $category) {?>
	      <li>
		<a href="search.php?keyword=<?= $api->getParameterValue('keyword') ?>&category=<?= $category->getId() ?>">
		  <?= $category->getName() ?>
		</a>
	      </li>
	    <?php }
	  ?>
	  </ul>
        </div>
        <div class="span10">
	  <?php
	    if ($api->hasParameter('keyword') and $api->getParameterValue('keyword') != '') {
	      print('<h2>Results for "' . $api->getParameterValue('keyword') . '"</h2>');
	    }
	    if ($api->hasParameter('merchant')) {
	      $merchants = $api->getMerchants();
	      print('From ' . $merchants[0]->getName() . '<br />');
	    }
	    print('Total returned results: ' . $api->getResultsCount());
	    generateBootstrapPagination($api);
	    print('<hr>');
	    foreach ($api->getProducts() as $product) {
	      renderProduct($product);
	    }
	  ?>
	  <?php generateBootstrapPagination($api) ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
