<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php");?>
    <title>ShopFoo Merchants</title>
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
	    $api = new PsApiCall($api_key, $catalog_key);
	    $api->get('merchants');
	    foreach($api->getCategories() as $category) { ?>
	      <li>
		<a href="<?= $api->getQueryString(array('category' => $category->getId(), 'alpha' => '', 'page' => '1')) ?>">
		  <?= $category->getName() ?>
		</a>
	      </li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span10">
	  <div class="offset2"><h1>Stores</h1></div>
	  <form name="alphachoose" method="get" action="merchants.php" style="float:right">
	     <select onchange="this.form.submit()" name="psapi_alpha" class="span1" id="alpha">
	      <option id="store-alpha-select-" value=''>Any</option>
	      <?php
	        foreach(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $letter) { ?>
		  <option id="store-alpha-select-<?= ord($letter)-64 ?>" value="<?= (ord($letter)-64) ?>">
		    <?= $letter ?>
		  </option>
		<?php } ?>
	     </select>
	  <?php if($api->hasParameter("alpha")) { ?>
	    <script language="javascript">
	      document.getElementById("store-alpha-select-".concat(<?= $api->getParameterValue("alpha") ?>)).setAttribute("selected", "true");
	    </script>
	  <?php } ?>
	  </form>
	  <hr>
	  <div class="pagination">
	    <ul>
	      <li><a href="<?= $api->prevPage() ?>">Prev</a></li>
	      <li><a href="#">Current page: <?php
	      if ($api->hasParameter('page')) {
		print($api->getParameterValue('page'));
	      } else {
		print('1');
	      } ?></a></li>
	      <li><a href="<?= $api->nextPage() ?>">Next</a></li>
	    </ul>
	  </div>
	  <div class="row">
	    <div class="span3 offset2">
	      <h4>Store</h4>
	    </div>
	    <div class="span2">
	      <h4>Coupons</h4>
	    </div>
	    <div class="span2">
	      <h4>Products</h4>
	    </div>
	  </div>
	  <table class="table-hover">
	    
	    
	    <?php
	      foreach ($api->getMerchants() as $merchant) {
		renderMerchant($merchant);
	      }
	    ?>
	  </table>
	  <hr />
	  <div class="pagination">
	    <ul>
	      <li><a href="<?= $api->prevPage() ?>">Prev</a></li>
	      <li><a href="#">Current page: <?php
	      if ($api->hasParameter('page')) {
		print($api->getParameterValue('page'));
	      } else {
		print('1');
	      } ?></a></li>
	      <li><a href="<?= $api->nextPage() ?>">Next</a></li>
	    </ul>
	  </div>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
