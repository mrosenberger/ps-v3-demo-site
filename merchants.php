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
	    require("include-before-call.php");
	      $api = new PsApiCall($api_key, $catalog_key, true);
	      $api->get('merchants');
	    require("include-after-call.php");
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
	  <?php generateBootstrapPagination($api) ?>
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
	  <?php generateBootstrapPagination($api) ?>
	  <a type="button" class="btn" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
