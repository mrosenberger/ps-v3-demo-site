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
        <div class="span10">
	  <!--
	  <div class="modal fade" id="inspect_modal">
	    <div class="modal-header">
	      <a class="close" data-dismiss="modal">&times;</a>
	      <h3>API Call and Logs</h3>
	    </div>
	    <div class="modal-body">
	      <p>Test Modal</p>
	    </div>
	    <div class="modal-footer">
	      <a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>
	    </div>
	  </div>
	  <a type="button" class="btn" href="#inspect_modal" data-toggle="modal">Inspect</a>
	  -->
	  <h3>About ShopFoo</h3>
	  <div class="well" style="padding-top:20px">
	    <img style="float:right;" src="http://www.popshops.com/images/v3/logo.page-blurb.gif" />
	    ShopFoo is intended as a demonstration of what the PopShops V3 API (and its accompanying PHP library) is capable of.<br />
	    
	    Click on the "inspect" link at the bottom of any page to see the API call performed, as well as a complete log of the PHP library's actions in rendering the page.<br />
	    You're welcome to download and reuse parts of this site on your own. See the link below to get a copy of the PHP.
	    <hr/>
	    <h4>Resources</h4>
	    <ul>
	      <li><a href="https://www.popshops.com/coupons-and-comparison-shopping">What is PopShops?</a></li>
	      <li><a href="http://www.popshops.com/support/api-3-overview">PopShops V3 API Documentation</a></li>
	      <li><a href="api-doc.php">PopShops PHP Library Documentation</a></li>
	      <li><a href="#">Download the PHP source of this site</a></li>
	    </ul>
	  </div>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>
