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
        </div>
        <div class="span10">
	  <?php
	    $api = new PsApiCall(array('account' => 'd1lg0my9c6y3j5iv5vkc6ayrd',
				       'catalog' => 'dp4rtmme6tbhugpv6i59yiqmr',
				       'logging' => true));
	    $api->get('categories');
	    $tree = $api->getCategoryTree();
	    foreach($tree->getChildren() as $child) {
	      print($child->getName() . '<br>');
	      foreach($child->getChildren() as $childchild) {
		print('==' . $childchild->getName() . '<br>');
	      }
	    }
	  ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
