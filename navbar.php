    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
	<div class="container">
	  <a class="brand">ShopFoo</a>
	  <div class="nav-collapse collapse">
	    <ul class="nav">
	      <li><a href="categories.php">Departments</a></li>
	      <li><a href="merchants.php?alpha=A">Stores</a></li>
	      <li><a href="#">Coupons</a></li>
	      <li><a href="#">About</a></li>
	    </ul>
	    <form class="navbar-form pull-right" method="get" action="search.php">
	      <input name="keyword" class="span2" type="text" placeholder="Search products...">
	      <select name="category" class="span3" id="category"> 
		<option value="" id="nav-cat-select-default">All products</option>
		<option value="32194" id="nav-cat-select-32194">Arts &amp; Crafts</option>
		<option value="2000" id="nav-cat-select-2000">Automotive Parts &amp; Vehicles</option>
		<option value="15000" id="nav-cat-select-15000">Baby &amp; Family</option>
		<option value="3000" id="nav-cat-select-3000">Clothing &amp; Accessories</option>
		<option value="5000" id="nav-cat-select-5000">Computers &amp; Software</option>
		<option value="7000" id="nav-cat-select-7000">Electronics</option>
		<option value="10000" id="nav-cat-select-10000">Events &amp; Tickets</option>
		<option value="12000" id="nav-cat-select-12000">Food, Flowers &amp; Gifts</option>
		<option value="13000" id="nav-cat-select-13000">Health &amp; Beauty</option>
		<option value="16000" id="nav-cat-select-16000">Home &amp; Garden</option>
		<option value="9000" id="nav-cat-select-9000">Mature &amp; Adult</option>
		<option value="21000" id="nav-cat-select-21000">Media</option>
		<option value="22000" id="nav-cat-select-22000">Musical Instruments</option>
		<option value="24000" id="nav-cat-select-24000">Office &amp; Professional Supplies</option>
		<option value="23000" id="nav-cat-select-23000">Pets &amp; Animal Supplies</option>
		<option value="25000" id="nav-cat-select-25000">Shoes &amp; Accessories</option>
		<option value="32346" id="nav-cat-select-32346">Specialty &amp; Novelty</option>
		<option value="27000" id="nav-cat-select-27000">Sports &amp; Outdoor Activities</option>
		<option value="31000" id="nav-cat-select-31000">Toys, Games &amp; Hobbies</option>
		<option value="32345" id="nav-cat-select-32345">Travel</option>
		<option value="8400" id="nav-cat-select-8400">Video Games, Consoles &amp; Accessories</option>
		<option value="9100" id="nav-cat-select-9100">Weapons</option>
		<?php
		  if(array_key_exists("category", $_GET)) {
		    $val = $_GET["category"];
		    print('<script language="javascript">document.getElementById("nav-cat-select-".concat(' . $val . ')).setAttribute("selected", "true")</script>' . "\n");
		  }
		?>
	      </select>
	      <button type="submit" class="btn">Search</button>
	    </form>
	  </div>
	</div>
      </div>
    </div>
    