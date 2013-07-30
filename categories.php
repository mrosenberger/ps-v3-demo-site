<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php");?>
    <title>ShopFoo</title>
    <?php
      function generate_cat_form($category) {
	$s = '';
	$s .= '<form class="form-search" method="get" action="search.php"><fieldset>';
        $s .= '<input name="keyword" class="span3" type="text" placeholder="Search ' . $category->getName() . '...">';
	$s .= '<input name="category" type="hidden" value="' . $category->getId() . '">';
	$s .= '<button type="submit" class="btn">Search</button>';
        $s .= '</fieldset></form>';
	return $s;
      }
    ?>
  </head>
  <body>
    <?php require("navbar.php");?>
    <div class="container">
      <div class="row">
        <div class="span2 sidebar">
        </div>
        <div class="span10">
          <h1>Categories</h1>
          <?php
	  require("include-before-call.php");
	    $api = new PsApiCall($api_key, $catalog_key, true);
	    $api->get('categories');
	  require("include-after-call.php");
	  $tree = $api->getCategoryTree();
	  foreach($tree->getChildren() as $child) { ?>
            <div class=row>
	      <div class=span10>
		<hr />
		<h3>
		  <a class="departments-category" href="search.php?psapi_keyword=&psapi_category=<?= $child->getId() ?>">
		    <i class="departments-icon <?= getCategoryIcon($child->getId()) ?>"></i>
		    <?= $child->getName() ?>
		  </a>
		</h3>
		<?php foreach($child->getChildren() as $childchild) { ?>
		  <a class="departments-subcategory"
		    href="search.php?psapi_keyword=&psapi_category=<?= $childchild->getId() ?>">
		    <?= $childchild->getName() ?>
		  </a>
		  <br />
		<?php } ?>
	      </div>
	    </div>
	  <?php } ?>
	  <a type="button" class="btn inspect-button" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
