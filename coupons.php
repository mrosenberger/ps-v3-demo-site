<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php"); ?>
    <title>Coupons</title>
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
	      foreach(sortByName($api->getDealTypes()) as $deal_type) {
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
		    <?php if ($checked) { print("<i class='sidebar-close-icon icon-remove'></i>"); } ?>
		    <small <?php if ($checked) { print('style="font-weight:bold;"'); } ?>>
		      <?= $deal_type->getName() . getCountHtml($deal_type->getCount()) ?>
		    </small>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
	  <span class="sidebar-heading">Focus on store:</span>
	  <ul class="sidebar-option-ul">
	    <?php
	      foreach(sortByName($api->getMerchants()) as $merchant) {
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
		    <?php if ($checked) { print("<i class='sidebar-close-icon icon-remove'></i>"); } ?>
		    <small <?php if ($checked) { print('style="font-weight:bold;"'); } ?>>
		      <?= $merchant->getName() . getCountHtml($merchant->getCount()) ?>
		    </small>
		  </a>
		</li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span9">
	  <?php
	    if ($api->hasParameter('keyword') and $api->getParameterValue('keyword') !== '') { ?>
	      <h2 class="search-results-header">
		<?= str_replace("100001", "Thousands of ", (string) $api->getResultsCount()) ?>
		coupon results for "<?= $api->getParameterValue('keyword') ?>"
	      </h2> <?php
	    } else { ?>
	      <h2 class="search-results-header">
		<i class="icon-fire hot-products-icon"> </i>
		<span class="hot-products-text">Hot</span>
		Coupons
	      </h2> <?php
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
	    $not_started = 0;
	    date_default_timezone_set("UTC");
	    $today_time = strtotime(date("Y-m-d"));
	    foreach ($api->getDeals() as $deal) {
	      if ($deal->getStartOn()) {
		$parts = explode("/", $deal->getStartOn());
		$start_time = strtotime($parts[2] . '-' . $parts[0] . '-' . $parts[1]);
		if ($start_time < $today_time) {
		  renderDeal($deal);
		} else {
		  //print('HAS NOT STARTED YET: ' . $deal->getStartOn() . '<br />');
		  $not_started++;
		  // It hasn't started yet
		}
	      } else {
		renderDeal($deal);
	      }
	    }
	    if ($not_started) {
	      print('Omitting ' . $not_started . ' coupons that are not yet active.');
	    }
	    generateBootstrapPagination($api);
	  ?>
	  <?php require('footer.php'); ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
