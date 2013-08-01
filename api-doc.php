<!DOCTYPE html>
<html>
    <head>
        <script type="application/x-javascript">
            function update_keys() {
                var api_key = document.getElementById("api-key-entry").value;
                var catalog_key = document.getElementById("catalog-key-entry").value;
                var fill_apis = document.getElementsByClassName("fill-api-key");
                var fill_catalogs = document.getElementsByClassName("fill-catalog-key");
                var i;
                for (i=0; i < fill_apis.length; i++) {
                    fill_apis[i].innerHTML = api_key;
                }
                for (i=0; i < fill_catalogs.length; i++) {
                    fill_catalogs[i].innerHTML = catalog_key;
                }
                return false;
            }
        </script>
        <style>
            body {
              padding-top: 60px;
              padding-bottom: 40px;
            }
            hr {
                color: #000000;
                background-color: #000000;
                height: 2px;
            }
            .anchor{
	      padding-top: 35px;
	    }
	    .anchor-objects{
	      padding-top: 65px;
	    }
	    .dl-horizontal dt 
	    {
	      white-space: normal;
	      width: 200px; 
	    }
        </style>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <title>PS API PHP Library</title>
    </head>
    <body>
        <?php require("navbar.php");?>
        <div class="container">
            <div class="row">
                <div data-spy="affix" class="span2 sidebar">
                    <h4>Tutorial</h4>
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#overview">Overview</a></li>
                        <li><a href="#gettingstarted">Getting Started</a></li>
                        <li><a href="#hellopopshops">Hello, Popshops</a></li>
                        <li><a href="#theapis">The PopShops APIs</a></li>
                        <li><a href="#urlmode">URL Parameters</a></li>
                        <li><a href="#troubleshooting">Troubleshooting</a></li>
                    </ul>
                    <h4>Objects</h4>
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#psapicall">PsApiCall</a></li>
                        <li><a href="#psapiproduct">PsApiProduct</a></li>
                        <li><a href="#psapioffer">PsApiOffer</a></li>
                        <li><a href="#psapimerchant">PsApiMerchant</a></li>
                        <li><a href="#psapideal">PsApiDeal</a></li>
                        <li><a href="#psapicategory">PsApiCategory</a></li>
                        <li><a href="#psapibrand">PsApiBrand</a></li>
                        <li><a href="#psapimerchanttype">PsApiMerchantType</a></li>
                        <li><a href="#psapidealtype">PsApiDealType</a></li>
                    </ul>
                </div>
                <div class="span10 offset2">
		    <img src="img/popshops-large.gif" class="popshops-large-logo" />
                    <h3>PopShops PHP API-V3 Library Documentation</h3>
                    <hr/>
                    <a class="anchor" name="overview"></a>
                    <h3>Overview</h3>
                    <div class="well">
                        PopShops provides a powerful <a href="http://www.popshops.com/support/api-3-overview">
                        RESTful API</a> to our <a href="http://www.popshops.com/signup">Data Pack</a> subscribers.<br/>
                        This API exposes our
                        <a href="#psapiproduct">products</a>,
                        <a href="#psapioffer">offers</a>,
                        <a href="#psapideal">deals</a>,
                        <a href="#psapibrand">brands</a>,
                        <a href="#psapimerchant">merchants</a>, and
                        <a href="#psapicategory">categories</a> through a JSON interface.<br/>
                        While it is entirely possible to use our API by itself, we also provide this PHP library which facilitates some common API functionality.<br/>
                    </div>
	            <a class="anchor" name="gettingstarted"></a>
                    <h3>Getting Started</h3>
                    <div class="well">
                        Before we begin, there's a few things you'll need to do:<br/><br/>
                        <ul>
                            <li>Select at least one merchant in your <a href="http://www.popshops.com/dashboard2/catalogs?v=3.0">catalog</a>.</li>
                            <li>Ensure that your web server is configured to properly recognize and serve PHP files.</li>
                            <li>Download <a href='#'>ps-v3-library.php</a> and move it to the directory you want to serve your site from.</li>
                            <li>Find your
                            <a href="http://www.popshops.com/dashboard2/settings/api_keys?v=3.0">API key</a> and
                            <a href="http://www.popshops.com/dashboard2/catalogs?v=3.0">catalog key.</a></li>
                            Enter them below and the demos on this page will become copy-paste runnable.
                            <form class="form-inline">
                                <fieldset>
                                    <input type="text" id="api-key-entry" placeholder="API Key"/>
                                    <input type="text" id="catalog-key-entry" placeholder="Catalog Key"/>
                                    <button type="button" class="btn btn-warning" href="#!" onclick="update_keys();">Update</button>
                                </fieldset>
                            </form>
                        </ul>
                    </div>
		    <a class="anchor" name="hellopopshops"></a>	 
                    <h3>Hello, PopShops</h3>
                    &nbsp;&nbsp;&nbsp;&nbsp;Create <i>demo.php</i> in the same directory as the library, and copy the following code into it:<br/>
                    <pre class="prettyprint">
&lt;?php
  require('ps-v3-library.php');                               // Include the library
  $api = new PsApiCall('<span class="fill-api-key">your_api_key</span>',
                       '<span class="fill-catalog-key">your_catalog_key</span>'); 
  $api->call('products', array('keyword' => 'ipad'));         // Perform the call, searching for 'ipad'
  foreach ($api->getProducts() as $product) {                 // Iterate through the returned products
    print($product->getName() . '&lt;br/&gt;');                     // Print the name of each one
  }
?&gt;</pre>
                    <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;Your page should render to something like this:
                    <br/><br/>
                    <div class="well">
                        Apple - iPad with Retina display Wi-Fi - 16GB - Black<br/>
                        Apple - iPad with Retina display Wi-Fi - 16GB - White<br/>
                        Apple iPad 2 with Wi-Fi 16GB White MC979LL/A<br/>
                        Apple - iPad with Retina display Wi-Fi - 128GB - White<br/>
                        Apple - iPad with Retina display Wi-Fi - 128GB - Black<br/>
                        ...
                    </div>
		    <a class="anchor" name="theapis"></a>
		    <h3>The PopShops APIs</h3>
		    <div class="well">
			The <a href="#psapicall">PsApiCall</a> object can perform calls to four distinct APIs:
			<dl class="dl-horizontal">
			    <dt>Products</dt>
			    <dd>
				Retrieve <a href="#psapiproduct">products</a> and their associated
				<a href="#psapioffer">offers</a>, <a href="#psapicategory">categories</a>,
				<a href="#psapimerchant">merchants</a>, <a href="#psapideal">deals</a>, and
				<a href="#psapibrand">brands</a>.
			    </dd>
			    <dd>
				Having instantiated a <code class="inline-code">PsApiCall</code> instance called <code class="inline-code">$api</code>, call this API as follows:
			    </dd>
			    <dd>
				<blockquote><code class="inline-code">$api->call('products', $parameters);</code></blockquote> where <code class="inline-code">$parameters</code>
				is an array of <i>$param=>$value</i> string pairs,
				such as: <code class="inline-code">array('keyword'=>'ipad', 'category'=>'7000')</code>
			    </dd>
			    <dd>
				These <i>$param</i>s can be any of the option request parameters listed 
				<a href="http://www.popshops.com/support/api-3-products">here</a>.
			    </dd>
			    <dt>Merchants</dt>
			    <dd>
				Retrieve <a href="#psapimerchant">merchants</a> and their associated
				<a href="#psapimerchanttype">merchant types</a>, <a href="#psapicategory">categories</a>, and
				<a href="#psapicountry">countries</a>.
			    </dd>
			    <dd>
				Having instantiated a <code class="inline-code">PsApiCall</code> instance called <code class="inline-code">$api</code>, call this API as follows:
			    </dd>
			    <dd>
				<blockquote><code class="inline-code">$api->call('merchants', $parameters);</code></blockquote> where <code class="inline-code">$parameters</code>
				is an array of <i>$param=>$value</i> string pairs,
				such as: <code class="inline-code">array('alpha'=>'p', 'network'=>'2')</code>
			    </dd>
			    <dd>
				These <i>$param</i>s can be any of the option request parameters listed 
				<a href="http://www.popshops.com/support/api-3-merchants">here</a>.
			    </dd>
			    <dt>Deals</dt>
			    <dd>
				Retrieve <a href="#psapideal">deals</a>
				and their associated <a href="#psapimerchant">merchants</a>,
				<a href="#psapidealtype">deal types</a>, and <a href="#psapicountry">countries</a>.
			    </dd>
			    <dd>
				Having instantiated a <code class="inline-code">PsApiCall</code> instance called <code class="inline-code">$api</code>, call this API as follows:
			    </dd>
			    <dd>
				<blockquote><code class="inline-code">$api->call('deals', $parameters);</code></blockquote> where <code class="inline-code">$parameters</code>
				is an array of <i>$param=>$value</i> string pairs,
				such as: <code class="inline-code">array('keyword'=>'laptop', 'deal_type'=>'6')</code>
			    </dd>
			    <dd>
				These <i>$param</i>s can be any of the option request parameters listed 
				<a href="http://www.popshops.com/support/api-3-coupons-deals">here</a>.
			    </dd>
			    <dt>Categories</dt>
			    <dd>
				Retrieve categories and construct a hierarchical tree, starting at the most
				general and descending into the very specific.
			    </dd>
			    <dd>
				Having instantiated a <code class="inline-code">PsApiCall</code> instance called <code class="inline-code">$api</code>, call this API as follows:
			    </dd>
			    <dd>
				<blockquote><code class="inline-code">$api->call('categories');</code></blockquote>
			    <dd><code class="inline-code">PsApiCall->getCategoryTree()</code> returns the topmost node of this tree.
			    </dd>
			    <dd>
				Each node supports the methods <code class="inline-code">getName()</code>, <code class="inline-code">getId()</code>, and
				<code class="inline-code">getChildren()</code>, the last of which returns a list of zero or more child nodes.
			    </dd>
			</dl>
		    </div>
		    <a class="anchor" name="urlmode"></a>
                    <h3>Passing parameters via the URL string</h3>
                    <div class="well">
                        To supplement the parameters (such as <code class="inline-code">'keyword' => 'backpack'</code>) passed to <code class="inline-code">PsApiCall->call</code>,
                        the library searches through the GET query string for any parameters starting with '<i>psapi_</i>' and
                        incorporates them (stripped of their prefix) into the API call.
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;Let's try it. Copy the following code into your <i>demo.php</i> file:<br/>
                    <pre class="prettyprint">
&lt;?php
  require('ps-v3-library.php');                                               // Include the library
  $api = new PsApiCall('<span class="fill-api-key">your_api_key</span>',
                       '<span class="fill-catalog-key">your_catalog_key</span>');                   
  $api->call('products');                                                     // Note that no params are passed
  foreach ($api->getProducts() as $product) {                                 // Iterate through products
    print($product->getName() . '&lt;br/&gt;');                                     // Print the name of each
  }
?&gt;</pre>
		    <div class="well">
                      Now, append <code class="inline-code">?psapi_keyword=backpack</code> to your request. The full request will look something like:<br/><br/>
                      <blockquote>http://localhost/demo.php?psapi_keyword=backpack</blockquote>
		      This technique is useful for a variety of applications, such as accepting input from HTML forms.
		      <!-- Add something about the precedence of parameters: call-time vs url-passed etc. -->
		    </div>
		    <div class="well">
		       The library can also do the "opposite": we can use it to generate query strings which,<br/>
                       having been served and then clicked, will later be read by the library.<br/><hr/>
		       The parameters included in the generated query string come from two places:<br/>
		       <ul>
			 <li>The internal options passed to <code class="inline-code">PsApiCall->call</code></li>
			 <li>The parameters passed to the library via the query string (includes both parameters <b>with</b>
			 and <b>without</b> the '<i>psapi_</i>' prefix)</li>
		       </ul>
                       This feature allows you to preserve any parameters that already exist in the URL string, while modifying others.<br/>
                       If you want to add to/edit the parameters included in the link, see <code class="inline-code">PsApiCall->getQueryString()</code> below.<br/><br/>
                       <strong>Note that these link generators do not return API call strings: they generate URLs with the same domain, path, and page name as the PHP page being served, but with the query parameters modified.</strong>
		    </div>
		    &nbsp;&nbsp;&nbsp;&nbsp;Let's add a link to the next page of results:<br/>
                    <pre class="prettyprint">
&lt;?php
  require('ps-v3-library.php');                                               // Include the library
  $api = new PsApiCall('<span class="fill-api-key">your_api_key</span>',
                       '<span class="fill-catalog-key">your_catalog_key</span>'); 
  $api->call('products');                                                     // Note that no params are passed
  foreach ($api->getProducts() as $product) {                                 // Iterate through products
    print($product->getName() . '&lt;br/&gt;');                                     // Print the name of each
  }
  // Create a link to this page, but with the 'page' parameter incremented:
  print('&lt;a href="' . $api->nextPage() . '"&gt;Next page&lt;/a&gt;');      
?&gt;</pre>
		    <div class="well">
		      All in all, <a href="#psapicall">PsApiCall</a> supports four such link generators:
		      <dl class="dl-horizontal">
			<dt><code class="inline-code">nextPage()</code></dt>
			<dd>Generates a link to the next page of results</dd>
			<dt><code class="inline-code">prevPage()</code></dt>
			<dd>Generates a link to the previous page of results</dd>
			<dt><code class="inline-code">paginate($page)</code></dt>
			<dd>Generates a link to the specified page of results (1-100)</dd>
			<dt><code class="inline-code">getQueryString($mods)</code></dt>
			<dd>Generates a link to the page with the $mods (map of $params=>$values) applied
			    <br/>&nbsp;&nbsp;For example: 
			    <br/>&nbsp;&nbsp;<code class="inline-code">$api->getQueryString(array('page' => 7))</code>
			    <br/>&nbsp;&nbsp;is equivalent to
			    <br/>&nbsp;&nbsp;<code class="inline-code">$api->paginate(7)</code>
			    <br/>&nbsp;&nbsp;This generator is much more powerful than the other three. 
			    <br/>&nbsp;&nbsp;It allows a link to be generated to a page with any set of parameters you want.
			</dd>
		      </dl>
                    </div>
		    <a class="anchor-objects" name="psapicall"></a>
                    <h3 style="display:inline">PsApiCall </h3>
                    &#8213; A one-shot call against the PopShops Products/Merchants/Deals/Categories API, and a container for the response values
                    <div class="well">
                        The central class of the PopShops API Library.
                        <br/>Allows you to construct, execute, and interpret the results of a call against one of four APIs:
                        <ul>
                            <li>Products</li>
                            <li>Merchants</li>
                            <li>Deals</li>
                            <li>Categories</li>
                        </ul>
                        <br/>A PsApiCall instance has the following public methods:
                        <dl>
			  <dt>call($call_type[, $arguments])</dt>
			  <dd>
			    $call_type must be one of 'products', 'merchants', 'deals', or 'categories'.<br/>
			    $arguments may optionally be passed as an array of parameter=>value pairs, such as:
			    <ul>
				<li>array('page'=>5, 'merchant'=>'1043')</li>
				<li>array('results_per_page'=>10, 'page'=>4, 'category'=>'403')</li>
				<li>array('merchant_type'=>4, 'results_per_page'=>25)</li>
			    </ul>
			    The library then uses the construct-time provided account key, catalog key, $call_type, and optional $arguments to perform a call against a PopShops Api.
			  </dd>
			  <dt>getProducts()</dt>
			  <dd>
			    Returns an array of <a href="#psapiproduct">PsApiProduct</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getOffers()</dt>
			  <dd>
			    Returns an array of <a href="#psapioffer">PsApiOffer</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getMerchants()</dt>
			  <dd>
			    Returns an array of <a href="#psapimerchant">PsApiMerchant</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getDeals()</dt>
			  <dd>
			    Returns an array of <a href="#psapideal">PsApiDeal</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getBrands()</dt>
			  <dd>
			    Returns an array of <a href="#psapibrand">PsApiBrand</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getCategories()</dt>
			  <dd>
			    Returns an array of <a href="#psapicategory">PsApiCategory</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getDealTypes()</dt>
			  <dd>
			    Returns an array of <a href="#psapidealtype">PsApiDealType</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getMerchantTypes()</dt>
			  <dd>
			    Returns an array of <a href="#psapimerchanttype">PsApiMerchantType</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getCountries()</dt>
			  <dd>
			    Returns an array of <a href="#psapicountry">PsApiCountry</a> objects parsed from the API response.<br />
			  </dd>
			  <dt>getProduct($id)</dt>
			  <dd>Retrieves the <a href="#psapiproduct">PsApiProduct</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getOffer($id)</dt>
			  <dd>Retrieves the <a href="#psapioffer">PsApiOffer</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getMerchant($id)</dt>
			  <dd>Retrieves the <a href="#psapimerchant">PsApiMerchant</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getDeal($id)</dt>
			  <dd>Retrieves the <a href="#psapideal">PsApiDeal</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getBrand($id)</dt>
			  <dd>Retrieves the <a href="#psapibrand">PsApiBrand</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getCategory($id)</dt>
			  <dd>Retrieves the <a href="#psapicategory">PsApiCategory</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getDealType($id)</dt>
			  <dd>Retrieves the <a href="#psapidealtype">PsApiDealType</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getMerchantType($id)</dt>
			  <dd>Retrieves the <a href="#psapimerchanttype">PsApMerchantType</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			  <dt>getCountry($id)</dt>
			  <dd>Retrieves the <a href="#psapicountry">PsApiCountry</a> object from the API response with the specified string $id. Returns NULL on failure.</dd>
			    <dt>nextPage()</dt>
			    <dd>Generates a link to the next page of results</dd>
			    <dt>prevPage()</dt>
			    <dd>Generates a link to the previous page of results</dd>
			    <dt>paginate($page)</dt>
			    <dd>Generates a link to the specified page of results (1-100)</dd>
			    <dt>getQueryString($mods)</dt>
			    <dd>Generates a link to the page with the $mods (map of $params=>$values) applied
				<br/>&nbsp;&nbsp;For example: 
				<br/>&nbsp;&nbsp;<code class="inline-code">$api->getQueryString(array('page' => 7))</code>
				<br/>&nbsp;&nbsp;is equivalent to
				<br/>&nbsp;&nbsp;<code class="inline-code">$api->paginate(7)</code>
				<br/>&nbsp;&nbsp;This generator is much more powerful than the other three. 
				<br/>&nbsp;&nbsp;It allows a link to be generated to a page with any set of parameters you want.
			    </dd>
			    <dt>hasParameter($parameter)</dt>
			    <dd>Returns true if the PsApiCall instance has been passed a parameter called $parameter.<br />
				These parameters may have come from two places:
				<ul>
				    <li>The array passed to PsApiCall->call</li>
				    <li>The HTTP GET parameters prefixed with 'psapi_' (see <a href="#urlmode">URL Parameters</a> above)
				</ul>
			    </dd>
			    <dt>getParameterValue($parameter)</dt>
			    <dd>Returns the value of the given $parameter (see hasParameter for more information on these parameters).<br />
				If no such parameter exists, returns NULL.
			    </dd>
			    <dt>getOptions()</dt>
			    <dd>Returns an array of the parameters that the PsApiCall has been configured with. See hasParameter for more information on these parameters.
			    </dd>
			    <dt>getUrlPrefix()</dt>
			    <dd>Returns the URL prefix (see <a href="#urlmode">URL Parameters</a>). Defaults to 'psapi_'.</dd>
			    <dt>getResultsCount()</dt>
			    <dd>Returns an integer representing the number of matching results. For example, a products call may return "500", meaning that 500 products were found to match. Not all of these products are guaranteed to be included in the API response; you may have to paginate through to get them all.</dd>
			    <dt>resource($resource)</dt>
			    <dd>
				An alternate way to access the arrays of API response objects. <br />
				For example, instead of calling "$api->getProducts()", you can call "$api->resource('products')" <br />
				The valid arguments are 'products, 'offers', 'merchants', 'deals', 'categories', 'deal_types', 'merchant_types', and 'countries'
			    </dd>
			    <dt>resourceById($resource, $id)</dt>
			    <dd>
				An alternate way to access individual API response objects. <br />
				For example, instead of calling "$api->getProduct('4412')", you can call "api->resourceById('product', '4412')" <br />
				See above method for the valid $resource values. $resource must be plural, as seen above.
			    </dd>
			</dl>
                    </div>
		    <a class="anchor-objects" name="psapiproduct"></a>
                    <h3 style="display:inline">PsApiProduct </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single Product
                    <div class="well">
                        In PopShops terminology, a Product is simply an item or service, like an iPad or a set of concert tickets.<br/>
                        An individual Product does not have a specific seller.
                        <a href="#psapimerchant">Merchants</a> such as Amazon.com or Barnes&Noble may have
                        <a href="#psapioffer">Offers</a> for a given Product.
                        <br/>It is these Offers that may actually be linked to and purchased by the consumer.
			<br />A PsApiProduct instance has the following public methods:
			<dl>
			    <dt>
			    </dt>
			</dl>
                    </div>
		    <a class="anchor-objects" name="psapioffer"></a>
                    <h3 style="display:inline">PsApiOffer </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single Offer
                    <div class="well">
                        An Offer is a
                        <a href="#psapiproduct">Product</a> being sold by a given
                        <a href="#psapimerchant">Merchant</a> for a certain price.
                    </div>
		    <a class="anchor-objects" name="psapimerchant"></a>
                    <h3 style="display:inline">PsApiMerchant </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single Merchant
                    <div class="well">
                        A Merchant is an organization which publishes
                        <a href="#psapioffer">Offers</a>, such as Amazon.com, Footlocker.com, or COMPUSA.
                    </div>
		    <a class="anchor-objects" name="psapideal"></a>
                    <h3 style="display:inline">PsApiDeal </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single Deal
                    <div class="well">
                        A Deal is a coupon or savings provided by a <a href="#psapimerchant">Merchant</a>, such as a percentage off or free shipping.
                    </div>
		    <a class="anchor-objects" name="psapicategory"></a>
                    <h3 style="display:inline">PsApiCategory </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single Category
                    <div class="well">
                        We've created a set of Categories to organize the vast array of <a href="#psapiproduct">Products</a> that we index.
                    </div>
		    <a class="anchor-objects" name="psapibrand"></a>
                    <h3 style="display:inline">PsApiBrand </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single Brand
                    <div class="well">
                        A Brand is a vendor or manufacturer who produces a line of goods, such as Sony or Toshiba.
                    </div>
		    <a class="anchor-objects" name="psapimerchanttype"></a>
                    <h3 style="display:inline">PsApiMerchantType </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single MerchantType
                    <div class="well">
                        
                    </div>
		    <a class="anchor-objects" name="psapidealtype"></a>
                    <h3 style="display:inline">PsApiDealType </h3>
                    &#8213; Represents and facilitates the use of returned API data for a single DealType
                    <div class="well">
                        
                    </div>
                </div>
            </div>
        </div>
        <?php require('bottom-js-includes.php');?>
        <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?skin=sunburst&lang=php"></script>
        <style>
            li.L0, li.L1, li.L2, li.L3, li.L5, li.L6, li.L7, li.L8, li.L9 {
                list-style-type: decimal !important;
            }
        </style>
    </body>
</html>
