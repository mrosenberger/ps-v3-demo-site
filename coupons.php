<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php"); ?>
    <title>ShopFoo</title>
  </head>
  <body>
    <?php
      require("navbar.php");
      require("include-before-call.php");
	$api = new PsApiCall($api_key, $catalog_key, true);
	$api->call('deals', array('results_per_page'=>'10'));
      require("include-after-call.php");
    ?>
    <div class="container">
      <div class="row">
        <div class="span3 sidebar">
	  <span class="sidebar-heading">Focus on type of deal:</span>
	  <ul class="sidebar-option-ul">
	    <?php
	      foreach($api->getDealTypes() as $deal_type) {
		$checked = ($api->hasParameter('deal_type') and ($deal_type->getId() == $api->getParameterValue('deal_type')));
		?>
		<li>
		  <a href="<?php
		  if ($checked) {
		    print($api->getQueryString(array('deal_type' => '', 'page' => '1')));
		  } else {
		    print($api->getQueryString(array('deal_type' => $deal_type->getId(), 'page' => '1')));
		  } ?>
		    ">
		    <input type="checkbox" <?php if ($checked) { print("checked"); } ?>>
		    <small><?= $deal_type->getName() ?> <span class="selection-count">(<?= $deal_type->getCount() ?>)</span></small>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
	  <span class="sidebar-heading">Focus on retailer:</span>
	  <ul class="sidebar-option-ul">
	    <?php
	      foreach($api->getMerchants() as $merchant) {
		$checked = ($api->hasParameter('merchant') and ($merchant->getId() == $api->getParameterValue('merchant')));
		?>
		<li>
		  <a href="<?php
		  if ($checked) {
		    print($api->getQueryString(array('merchant' => '', 'page' => '1')));
		  } else {
		    print($api->getQueryString(array('merchant' => $merchant->getId(), 'page' => '1')));
		  } ?>
		    ">
		    <input type="checkbox" <?php if ($checked) { print("checked"); } ?>>
		    <small><?= $merchant->getName() ?> <span class="selection-count">(<?= $merchant->getCount() ?>)</span></small>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span9">
	  <?php
	    if ($api->hasParameter('keyword') and $api->getParameterValue('keyword') !== '') {
	      print('<h2 class="search-results-header">' . $api->getResultsCount() .
		    ' coupon results for "' . $api->getParameterValue('keyword') . '"</h2>');
	    } else {
	      print('<h2 class="search-results-header">Coupon results</h2>');
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
		  <input class="span2" type="text" name="psapi_keyword" placeholder="Refine keywords..." autofocus>
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
	    foreach ($api->getDeals() as $deal) {
	      renderDeal($deal);
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
