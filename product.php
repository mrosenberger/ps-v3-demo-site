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
    <title>ShopFoo</title>
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
	    foreach($api->getCategories() as $category) { ?>
	      <li>
		<a href="search.php?psapi_keyword=&psapi_category=<?= $category->getId() ?>">
		  <?= $category->getName() ?>
		</a>
	      </li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span10">
	  <h2><?= $p->getName() ?></h2>
	  <hr>
	  <div class="row">
	    <div class="span10">
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
	  <div class="row">
	    <div class="span12">
	      <hr>
	      <h2>
		Offers
	      </h2>
	    </div>
	  </div>
	  <table class="table-hover offers-table">
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
	  <a type="button" class="btn inspect-button" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
