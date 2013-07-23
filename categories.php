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
    <script language="javascript">
    
    </script>
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
	    $api = new PsApiCall(array('account' => 'd1lg0my9c6y3j5iv5vkc6ayrd',
				       'catalog' => 'dp4rtmme6tbhugpv6i59yiqmr',
				       'logging' => false));
	    $api->get('categories');
	    $tree = $api->getCategoryTree();
	    foreach($tree->getChildren() as $child) {
              print("<div class=row><div class=span10>");
              print("<hr>");
	      print("<h3><a href=search.php?keyword=&category=" . $child->getId() . ">"  . $child->getName() . '</a></h3><br>');
              //print('<form method="get" action="search.php">');
              //  print('<input name="keyword" class="span2" type="text" placeholder="Search ' . $child->getName() . '...">');
              //print("</form>");
	      print(generate_cat_form($child));
	      foreach($child->getChildren() as $childchild) {
		print($childchild->getName() . '<br>');
		print(generate_cat_form($childchild));
	      }
              print("</div></div>");
            }
            ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
