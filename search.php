<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php");?>
    <title>ShopFoo</title>
  </head>
  <body>
    <?php
      require("navbar.php");
      require("include-before-call.php");
	$api = new PsApiCall($api_key, $catalog_key, true);
	$api->get('products', array('results_per_page'=>'10'));
      require("include-after-call.php");
    ?>
    <div class="container">
      <div class="row">
        <div class="span3 sidebar">
	  <h6>Focus on category:</h6>
	  <ul class="sidebar-option-ul">
	    <?php
	      foreach($api->getCategories() as $category) {
		$checked = ($api->hasParameter('category') and ($category->getId() == $api->getParameterValue('category')));
		?>
		<li>
		  <a href="<?php
		  if ($checked) {
		    print($api->getQueryString(array('category' => '', 'page' => '1')));
		  } else {
		    print($api->getQueryString(array('category' => $category->getId(), 'page' => '1')));
		  } ?>
		    ">
		    <input type="checkbox" <?php if ($checked) { print("checked"); } ?>>
		    <small><?= $category->getName() ?></small>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
	  <h6>Focus on brand:</h6>
	  <ul class="sidebar-option-ul">
	    <?php
	      foreach($api->getBrands() as $brand) {
		$checked = ($api->hasParameter('brand') and ($brand->getId() == $api->getParameterValue('brand')));
		?>
		<li>
		  <a href="<?php
		  if ($checked) {
		    print($api->getQueryString(array('brand' => '', 'page' => '1')));
		  } else {
		    print($api->getQueryString(array('brand' => $brand->getId(), 'page' => '1')));
		  } ?>
		    ">
		    <input type="checkbox" <?php if ($checked) { print("checked"); } ?>>
		    <small><?= $brand->getName() ?> <span class="selection-count">(<?= $brand->getCount() ?>)</span></small>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span9">
	  <?php
	    if ($api->hasParameter('keyword') and $api->getParameterValue('keyword') !== '') {
	      print('<h2 class="search-results-header">' . str_replace("100001", "Thousands of ", (string) $api->getResultsCount()) .
		    ' results for "' . $api->getParameterValue('keyword') . '"</h2>');
	    } else {
	      print('<h2 class="search-results-header">Results</h2>');
	    }
	    if ($api->getResultsCount() == 0) { ?>
	      <br /><br />
	      <button class="btn" onclick="window.history.back()"><i class="icon-hand-left"> </i> Go back</a>
	      <?php
	      die();
	    }
	    if ($api->hasParameter('merchant') and $api->getParameterValue('merchant') !== '') {
	      print(' <h4 class="search-results-merchant">&#8213; From ' .
		    $api->getMerchant($api->getParameterValue('merchant'))->getName() . '</h4>');
	    }
	    if ($api->hasParameter('category') and $api->getParameterValue('category') !== '') {
	      print(' <h4 class="search-results-category">&#8213; In ' .
		    $api->getCategory($api->getParameterValue('category'))->getName() . '</h4>');
	    }
	  ?>
	  <div>
	    <form class="search-refine-keywords">
	      <fieldset>
		<?php generateHiddenParameters($api, array('keyword')) ?>
		<div class="input-append">
		  <input class="span2" type="text" name="psapi_keyword" placeholder="Refine keywords...">
		  <button class="btn btn-warning" type="submit"><i class="icon-search"> </i></button>
		</div>
	      </fieldset>
	    </form>
	    <?php
	      generateBootstrapPagination($api, 8, false);
	    ?>
	  </div>
	  <br />
	  <?php
	    foreach ($api->getProducts() as $product) {
	      renderProduct($product);
	    }
	    generateBootstrapPagination($api);
	  ?>
	  <a type="button" class="btn inspect-button" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
