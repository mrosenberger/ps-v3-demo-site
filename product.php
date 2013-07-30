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
	  <ul>
	  <?php
	    require("include-before-call.php");
	      $api = new PsApiCall($api_key, $catalog_key, true);
	      $api->get('products');
	    require("include-after-call.php");
	    $p = $api->getProducts();
            $p = $p[0];
	    foreach($api->getCategories() as $category) { ?>
	      <li>
		<a href="#">
		  <?= $category->getName() ?>
		</a>
	      </li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span10">
	  <h2><?=$p->attr('name')?></h2>
	  <hr>
	  <div class="row">
	    <div class="span10">
	      <a href="<?=$p->largestImageUrl()?>">
		<img class="product-detail-large-image" src="<?=$p->largestImageUrl()?>">
	      </a>
	      <?=$p->attr('description')?>
	      <br><br>
	      Brand: <i><?=$p->resource('brand')->attr('name')?></i>
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
	  <div class="row">
	    <div class="span2">
	      <h3>Store</h3>
	    </div>
	    <div class="span2">
	      <h3>Price</h3>
	    </div>
	    <div class="span2">
	      <h3>Condition</h3>
	    </div>
	  </div>
	  <table class="table-hover">
	    <?php
	      foreach($p->resource('offers') as $offer) {
		renderOffer($offer);
	      }
	    ?>
	  </table>
	  <a type="button" class="btn" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
