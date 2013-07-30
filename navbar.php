    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
	<div class="container">
	  <a class="brand">ShopFoo</a>
	  <div class="nav-collapse collapse">
	    <ul class="nav">
	      <li><a href="categories.php">Departments</a></li>
	      <li><a href="merchants.php?psapi_alpha=1">Stores</a></li>
	      <li><a href="#">Coupons</a></li>
	      <li><a href="about.php">About</a></li>
	    </ul>
	    <form class="navbar-form pull-right" method="get" action="search.php?">
	      <input name="psapi_keyword" class="span2" type="text" placeholder="Search products...">
	      <select name="psapi_category" class="span3" id="category">
		<option value="" id="nav-cat-select-default" class="nav-category-option">All products</option>
		<option value="32194" id="nav-cat-select-32194" class="nav-category-option">Arts &amp; Crafts</option>
		<option value="2000" id="nav-cat-select-2000" class="nav-category-option">Automotive Parts &amp; Vehicles</option>
		<option value="15000" id="nav-cat-select-15000" class="nav-category-option">Baby &amp; Family</option>
		<option value="3000" id="nav-cat-select-3000" class="nav-category-option">Clothing &amp; Accessories</option>
		<option value="5000" id="nav-cat-select-5000" class="nav-category-option">Computers &amp; Software</option>
		<option value="7000" id="nav-cat-select-7000" class="nav-category-option">Electronics</option>
		<option value="10000" id="nav-cat-select-10000" class="nav-category-option">Events &amp; Tickets</option>
		<option value="12000" id="nav-cat-select-12000" class="nav-category-option">Food, Flowers &amp; Gifts</option>
		<option value="13000" id="nav-cat-select-13000" class="nav-category-option">Health &amp; Beauty</option>
		<option value="16000" id="nav-cat-select-16000" class="nav-category-option">Home &amp; Garden</option>
		<option value="9000" id="nav-cat-select-9000" class="nav-category-option">Mature &amp; Adult</option>
		<option value="21000" id="nav-cat-select-21000" class="nav-category-option">Media</option>
		<option value="22000" id="nav-cat-select-22000" class="nav-category-option">Musical Instruments</option>
		<option value="24000" id="nav-cat-select-24000" class="nav-category-option">Office &amp; Professional Supplies</option>
		<option value="23000" id="nav-cat-select-23000" class="nav-category-option">Pets &amp; Animal Supplies</option>
		<option value="25000" id="nav-cat-select-25000" class="nav-category-option">Shoes &amp; Accessories</option>
		<option value="32346" id="nav-cat-select-32346" class="nav-category-option">Specialty &amp; Novelty</option>
		<option value="27000" id="nav-cat-select-27000" class="nav-category-option">Sports &amp; Outdoor Activities</option>
		<option value="31000" id="nav-cat-select-31000" class="nav-category-option">Toys, Games &amp; Hobbies</option>
		<option value="32345" id="nav-cat-select-32345" class="nav-category-option">Travel</option>
		<option value="8400" id="nav-cat-select-8400" class="nav-category-option">Video Games, Consoles &amp; Accessories</option>
		<option value="9100" id="nav-cat-select-9100" class="nav-category-option">Weapons</option>
		<?php if(array_key_exists("psapi_category", $_GET)) { ?>
		  <script language="javascript">
		    document.getElementById("nav-cat-select-".concat(<?= $_GET["psapi_category"] ?>)).setAttribute("selected", "true");
		  </script>
		<?php } ?>
	      </select>
	      <button type="submit" class="btn">Search</button>
	    </form>
	  </div>
	</div>
      </div>
    </div>
    