<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php"); ?>
    <title>Departments</title>
  </head>
  <body>
    <?php
      require("navbar.php");
      require("include-before-call.php");
	$api = new PsApiCall($api_key, $catalog_key, true);
	$api->call('categories');
      require("include-after-call.php");
      $tree = $api->getCategoryTree();
    ?>
    <div class="container">
      <div class="row">
        <div data-spy="affix" class="span3 sidebar">
	  <ul class="nav nav-stacked">
	    <?php
	      foreach ($tree->getChildren() as $child) { ?>
		<li class="departments-sidebar-li">
		  <a href="#anchor-<?= $child->getId() ?>">
		    <i class="<?= getCategoryIcon($child->getId()) ?>"> </i>
		    <?= $child->getName() ?>
		  </a>
		</li>
		<?php
	      }
	    ?>
	  </ul>
	</div>
        <div class="span9 offset3">
          <h1>Departments</h1>
          <?php
	    foreach($tree->getChildren() as $child) { ?>
	      <div class=row>
		<div class=span9>
		  <hr />
		  <h3>
		    <a name="anchor-<?= $child->getId() ?>" class="departments-category"
		      href="search.php?psapi_keyword=&psapi_category=<?= $child->getId() ?>">
		      <i class="departments-icon <?= getCategoryIcon($child->getId()) ?>"></i>
		      <?= $child->getName() ?>
		    </a>
		  </h3>
		  <?php foreach($child->getChildren() as $childchild) { ?>
		    <a class="departments-subcategory"
		      href="search.php?psapi_keyword=&psapi_category=<?= $childchild->getId() ?>">
		      <?= $childchild->getName() ?>
		    </a>
		    <br /> <?php 
} ?>
		</div>
	      </div>
	      <?php
	    }
	  ?>
	  <?php require('footer.php'); ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php'); ?>
  </body>
</html>
