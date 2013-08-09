<!DOCTYPE html>
<html>
  <head>
    <?php
      require("header-titleless.php");
      require("include-before-call.php");
	$api = new PsApiCall($api_key, $catalog_key, true);
	$api->call('products');
      require("include-after-call.php");
      $p = $api->getProducts();
      $p = $p[0];
    ?>
    <title><?= $p->getName() ?></title>
  </head>
  <body>
    <?php require("navbar.php");?>
    <div class="container">
      <div class="row">
        <div class="span3 sidebar">
	  <h3 class="product-deals-header">Available Coupons</h3>
	  <hr />
	  <?php
	    date_default_timezone_set("UTC");
	    $today_time = strtotime(date("Y-m-d"));
	    foreach ($api->getDeals() as $deal) {
	      if ($deal->getStartOn()) {
		$parts = explode("/", $deal->getStartOn());
		$start_time = strtotime($parts[2] . '-' . $parts[0] . '-' . $parts[1]);
		if ($start_time < $today_time) {
		  renderDealSidebar($deal);
		}
	      } else {
		renderDealSidebar($deal);
	      }
	    }
	  ?>
        </div>
        <div class="span9">
	  <h2><?= $p->getName() ?></h2>
	  <hr>
	  <div class="row">
	    <div class="span9">
	      <a href="<?= $p->largestImageUrl() ?>">
		<img class="product-detail-large-image" src="<?= $p->largestImageUrl() ?>">
	      </a>
	      <span class="product-detail-description">
		<?= $p->getDescription() ?>
	      </span>
	      <br><br>
	      <span class="brand-label">Brand: </span><span class="brand-value"><?= $p->getBrand()->getName() ?></span>
	    </div>
	  </div>
	  <hr />
	  <h2>
	    Offers
	  </h2>
	  <table class="table-hover">
	    <tr>
	      <td class="span2 offer-table-header">
		Store
	      </td>
	      <td align="center" class="span2 offer-table-header">
		Price
	      </td>
	      <td align="center" class="span2 offer-table-header">
		Condition
	      </td>
	    </tr>
	    <?php
	      foreach($p->getOffers() as $offer) {
		renderOffer($offer);
	      }
	    ?>
	  </table>
	  <?php require('footer.php'); ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
