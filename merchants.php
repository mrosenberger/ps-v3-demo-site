<!DOCTYPE html>
<html>
  <head>
    <?php
      require("header-titleless.php");
      require("include-before-call.php");
	$api = new PsApiCall($api_key, $catalog_key, true);
	$api->get('merchants');
      require("include-after-call.php");
    ?>
    <title>ShopFoo Merchants</title>
  </head>
  <body>
    <?php require("navbar.php");?>
    <div class="container">
      <div class="row">
        <div class="span3 sidebar">
	  <h6>Select store category:</h6>
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
		    print($api->getQueryString(array('category' => $category->getId(), 'page' => '1', 'alpha' => '')));
		  } ?>
		    ">
		    <input type="checkbox" <?php if ($checked) { print("checked"); } ?>>
		    <small><?= $category->getName() ?> <span class="selection-count">(<?= $category->getCount() ?>)</span></small>
		  </a>
		</li>
		<?php
	      }
	    ?>
	  </ul>
        </div>
        <div class="span9">
	  <h2>
	    <?php
	      if ($api->hasParameter('category')) {
		if ($api->getParameterValue('category') != '') {
		  if ($api->getCategory($api->getParameterValue('category'))) {
		    print($api->getCategory($api->getParameterValue('category'))->getName());
		  }
		}
	      }
	    ?>
	    Stores
	  </h2>
	  <form name="alphachoose" method="get" action="merchants.php" style="float:right">
	    <?php generateHiddenParameters($api, array('alpha', 'page')); ?>
	    <select onchange="this.form.submit()" name="psapi_alpha" class="span1" id="alpha">
	      <option id="store-alpha-select-" value=''>Any</option>
	      <?php
	        foreach(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $letter) { ?>
		  <option id="store-alpha-select-<?= ord($letter)-64 ?>" value="<?= (ord($letter)-64) ?>">
		    <?= $letter ?>
		  </option>
		  <?php
		}
	      ?>
	     </select>
	  <?php
	    if($api->hasParameter("alpha")) { ?>
	      <script language="javascript">
		document.getElementById("store-alpha-select-".concat(<?= $api->getParameterValue("alpha") ?>)).setAttribute("selected", "true");
	      </script>
	      <?php
	    }
	  ?>
	  </form>
	  <hr>
	  <?php generateBootstrapPagination($api) ?>
	  <table class="table-hover">
	    <tr>
	      <td class="span2">
	      </td>
	      <td class="span4 merchant-table-header">
		Store
	      </td>
	      <td class="span2 merchant-table-header">
		Products
	      </td>
	      <td class="span2 merchant-table-header">
		Coupons
	      </td>
	    </tr>	  
	    <?php
	      foreach ($api->getMerchants() as $merchant) {
		renderMerchant($merchant);
	      }
	    ?>
	  </table>
	  <hr />
	  <?php generateBootstrapPagination($api) ?>
	  <a type="button" class="btn inspect-button" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
