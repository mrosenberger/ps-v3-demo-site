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
	    require("include-before-call.php");
	      $api = new PsApiCall($api_key, $catalog_key, true);
	      $api->get('products');
	    require("include-after-call.php");
	    foreach($api->getCategories() as $category) {?>
	      <li>
		<a href="<?= $api->getQueryString(array('category' => $category->getId())) ?>">
		  <?= $category->getName() ?>
		</a>
	      </li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span10">
	  <?php
	    if ($api->hasParameter('keyword') and $api->getParameterValue('keyword') != '') {
	      print('<h2 style="display:inline">' . $api->getResultsCount() .
		    ' results for "' . $api->getParameterValue('keyword') . '"</h2>');
	    } else {
	      print('<h2 style="display:inline">Results</h2>');
	    }
	    if ($api->hasParameter('merchant')) {
	      print(' <h4 style="display:inline">&#8213; From ' .
		    $api->getMerchant($api->getParameterValue('merchant'))->getName() . '</h4><br />');
	    }
	    ?>
	    <div>
	      <form style="float:left;padding-right:20px;">
		<fieldset>
		  <?php generateHiddenParameters($api, array('keyword')) ?>
		  <div class="input-append">
		    <input class="span2" type="text" name="psapi_keyword" placeholder="Refine keywords...">
		    <button class="btn" type="submit">Search</button>
		  </div>
		</fieldset>
	      </form>
	      <?php
		generateBootstrapPagination($api, 5, false);
	      ?>
	    </div>
	    <hr />
	    <?php
	    foreach ($api->getProducts() as $product) {
	      renderProduct($product);
	    }
	    generateBootstrapPagination($api);
	  ?>
	  <a type="button" class="btn" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
