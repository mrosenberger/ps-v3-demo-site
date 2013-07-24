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
	    $api = new PsApiCall(array(
				   'account' => 'd1lg0my9c6y3j5iv5vkc6ayrd',
				   'catalog' => 'dp4rtmme6tbhugpv6i59yiqmr',
				   'logging' => false
				   ));
	    $api->get('products', array('product' => $_GET['product']));
	    $p = $api->resource('products');
            $p = $p[0];
	    foreach($api->resource('categories') as $category) { ?>
	      <li>
		<a href="#">
		  <?=$category->attr('name');?>
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
		<img style="float:left;max-width:300px;padding:20px;" src="<?=$p->largestImageUrl()?>">
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
	  <table>
	  <?php
	    foreach($p->resource('offers') as $offer) {
	      renderOffer($offer);
	    }
	  ?>
	  </table>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
