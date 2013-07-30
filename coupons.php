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
	  <h6>Focus deal type:</h6>
	  <ul>
	    <?php
	      require("include-before-call.php");
		$api = new PsApiCall($api_key, $catalog_key, true);
		$api->get('deals');
	      require("include-after-call.php");
	      foreach($api->getDealTypes() as $deal_type) {?>
		<li>
		  <a href="<?= $api->getQueryString(array('deal_type' => $deal_type->getId())) ?>">
		    <?= $deal_type->getName() ?>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
	  <h6>Focus merchant:</h6>
	  <ul>
	    <?php
	      foreach($api->getMerchants() as $merchant) {?>
		<li>
		  <a href="<?= $api->getQueryString(array('merchant' => $merchant->getId())) ?>">
		    <?= $merchant->getName() ?>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span10">
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
		  <input class="span2" type="text" name="psapi_keyword" placeholder="Refine keywords...">
		  <button class="btn" type="submit">Search coupons</button>
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
