<?php

// ================ Musings ================

// Maybe provide a nice tree structure pre-built for categories? --Not viable, not enough categories returned with API call
// Easy way to explain attr vs resource in the documentation: attr gives you values, resource gives you objects
// Instead of "resource", maybe there really should be individual methods for each, because often, you need to pass an argument
// For example, offers for a given merchant AND product
// Ask about categories: context vs. matches, any sort of tree structure (even just parents)
// Set up a better log system... Flag for logging to page vs. logging to system error log? Something like that. Fine for now.
// Need to maintain order of elements in internal arrays...
// Ask about why not all referenced categories are returned. For example, do a products search for keyword 'fork'
// Some requests taking upwards of 4 seconds
// Internal processing time isn't the issue; it's the API response time
// Behavior when merchant/category isn't present, even though it's referenced somewhere in the API results

// ================  Code   ================

// Logger: A very, very basic logging class. Implements two log levels:
//   -info   (providing status updates and debug information)
//   -error  (providing information on critical, generally fatal errors)

class PsApiLogger {

  private $enable_flag; // If true, logging proceeds as usual. If false, logging is disabled.

  public function __construct() {
    date_default_timezone_set('America/Los_Angeles');
    $this->enable_flag = false; // Default behavior is no logging, this is PHP after all!
  }

  public function info($s) {
    if ($this->enable_flag) {
      print(date(DATE_RFC822) . ' INFO: ' . $s . '<br>');
    }
  }

  public function error($s) {
    if ($this->enable_flag) {
      print(date(DATE_RFC822) . ' ERROR: ' . $s . '<br>'); // Log to the page
      error_log(date(DATE_RFC822) . ' ERROR: ' . $s . '<br>'); // Log to the webserver's error log
    }
  }

  public function enable() {
    $this->enable_flag = true;
    $this->info('Logging enabled.');
  }

  public function disable() {
    $this->info('Logging disabled.');
    $this->enable_flag = false;
  }

}

// PsApiCall: A one-shot API request against PopShops' Merchants, Products, or Deals API
class PsApiCall {

  // Associative arrays mapping ids to objects
  private $merchants;      // Associative array mapping ids to merchants.
  private $products;       // Associative array mapping ids to products.
  private $deals;          // Associative array mapping ids to deals.
  private $offers;         // Associative array mapping ids to offers.
  private $categories;     // Associative array mapping ids to categories.
  private $brands;         // Associative array mapping ids to brands.
  private $deal_types;     // Associative array mapping ids to deal_types.
  private $countries;      // Associative array mapping ids to countries.
  private $merchant_types; // Associative array mapping ids to merchant_types.
  private $category_tree;  // Special case data structure allowing for a nice, tree based approach to looking at categories

  // Various internal fields
  private $options;      // Associative array of option=>value pairs to be passed to the API when called.
  private $call_type;    // One of ['merchants', 'products', 'deals']. Specifies which api will be called.
  private $called;       // Set to true once API has been called once. Enforces single-use behavior of the PsApiCall object.
  private $logger;       // A Logger object used to log progress and errors
  private $url_prefix; // If url mode is enabled, this specifies the prefix that is prepended to all parameters
  private $results_count; // A count of the number of results returned, an integer

  // For statistics and analysis
  private $start_time;             // Time that PsApiCall->get was called
  private $response_received_time; // Time that the API response was received

  // Constructs a PsApiCall object using the provided api key and catalog id.
  public function __construct_old($options) {

    $this->logger = new PsApiLogger;

    $this->url_prefix = 'psapi_';

    foreach ($options as $option=>$value) {
      switch ($option) {
      case 'logging':
	if ($options['logging']) $this->logger->enable();
	break;
      case 'url-mode-prefix':
	if (strlen($value) > 0) {
	  $this->url_prefix = $value;
	} else {
	  $this->logger->error('Invalid url mode prefix. Must be at least one character.');
	}
	break;
      case 'account':
	$this->options['account'] = $value;
	break;
      case 'catalog':
	$this->options['catalog'] = $value;
	break;
      }
    }

    $this->called = false;

    $resources = array('merchants', 'products', 'deals', 'offers', 'categories', 'brands', 'deal_types', 'countries', 'merchant_types');
    foreach ($resources as $resource) {
      $this->{$resource} = array();
    }
  }
  
   // Constructs a PsApiCall object using the provided api key and catalog id.
  public function __construct($account, $catalog=NULL, $logging=false, $url_prefix='psapi_') {

    $this->logger = new PsApiLogger;
    $this->options['account'] = $account;
    if (isset($catalog)) $this->options['catalog'] = $catalog;
    if ($logging) $this->logger->enable();
    $this->url_prefix = $url_prefix;
    $this->called = false;
    $this->results_count = 0;
    $resources = array('merchants', 'products', 'deals', 'offers', 'categories', 'brands', 'deal_types', 'countries', 'merchant_types');
    foreach ($resources as $resource) {
      $this->{$resource} = array();
    }
  }

  private function loadOptionsGeneric($valid_options) {
    foreach ($_GET as $opt=>$val) {
      if (strpos($opt, $this->url_prefix) == 0) {
	$right = substr($opt, strlen($this->url_prefix));
	if (in_array($right, $valid_options)) {
	  $this->logger->info('Option "' . $right . '" loaded from url with value="' . $val . '"');
	  $this->options[$right] = $val;
	}
      }
    }
  }

  private function loadOptionsFromGetParams() {
    $valid_products_call_params = array('category', 'include_discounts', 'keyword', 'keyword_description', 'keyword_ean', 'keyword_identifier',
				       'keyword_isbn', 'keyword_mpn', 'keyword_name', 'keyword_person', 'keyword_upc', 'keyword_sku',
				       'merchant', 'merchant_type', 'page', 'percent_off', 'percent_off_max', 'percent_off_min', 'postal_code',
				       'price', 'price_max', 'percent_off_min', 'product', 'product_spec', 'include_identifiers',
				       'results_per_page', 'session', 'tracking_id', 'brand');
    $valid_merchants_call_params = array('alpha', 'category', 'keyword', 'merchant', 'network', 'page', 'results_per_page', 'tracking_id');
    $valid_deals_call_params = array('end_on', 'end_on_max', 'end_on_min', 'deal_type', 'keyword', 'keyword_description', 'keyword_name',
				     'merchant', 'merchant_type', 'page', 'results_per_page', 'session', 'site_wide', 'sort_deal',
				     'start_on', 'start_on_max', 'start_on_min',  'tracking_id');
    $valid_categories_call_params = array();
    $this->loadOptionsGeneric(${'valid_' . $this->call_type . '_call_params'});
  }

  private function getQueryParamString($option_modifications=array()) {
    if (! $this->called) {
      $this->logger->error('PopShops API error: Attempt to get query string before performing API call.');
      return 'PopShops API error: Attempt to get query string before performing API call.';
    }
    $tmp_options = $this->options;
    foreach ($option_modifications as $opt=>$value) {
      $tmp_options[$opt] = $value;
    }
    $output_params = array();
    // output_params has two sets of entries: options from the internal options array (without account and catalog), and
    //   options passed as part of the $_GET (which aren't used at all by this library, but are nice to pass along anyways)
    foreach ($tmp_options as $opt=>$value) { // First add the internal options (prefixed with the url mode prefix)
      if (($opt != 'account') and ($opt != 'catalog')) {
	$output_params[$this->url_prefix . $opt] = $value;
      }
    }
    foreach ($_GET as $param=>$value) { // Now add the non-internal params (not used interally, but it's friendly to pass them along)
      if (strpos($param, $this->url_prefix) === 0) {
	// Skip over any params with the prefix
      } else {
	$output_params[$param] = $value;
      }
    }
    $result_string = '';
    $first = true;
    foreach ($output_params as $opt=>$value) {
      if ($first) {
	$first = false;
      } else {
	$result_string .= '&';
      }
      $result_string .= $opt . '=' . $value;
    }
    return $result_string;
  }

  public function getQueryString($option_modifications=array()) {
    $param_string = $this->getQueryParamString($option_modifications);
    $host = $_SERVER['SERVER_NAME'];
    $path = explode('?', $_SERVER['REQUEST_URI']);
    $path = $path[0];
    try {
      $protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
      $protocol = strtolower($protocol[0]);
      return $protocol . '://' . $host . $path . '?' . $param_string;
    } catch (Exception $e) {
      return $host . $path . '?' . $param_string;
    }
  }
  
  public function hasParameter($param) {
    return array_key_exists($param, $this->options);
  }
  
  public function getParameterValue($param) {
    if (array_key_exists($param, $this->options)) {
      return $this->options[$param];
    } else {
      return NULL;
    }
  }

  public function paginate($page) { // Page can be an integer or a string representing an integer
    $page = (string) $page;
    return $this->getQueryString(array('page' => $page));
  }

  public function nextPage() {
    if (isset($this->options['page'])) {
      if (intval($this->options['page']) > 99) {
	return $this->getQueryString(array('page' => 100));
      } else {
	return $this->getQueryString(array('page' => ($this->options['page'] + 1)));
      }
    } else {
      return $this->getQueryString(array('page' => 2));
    }
  }

  public function prevPage() {
    if (isset($this->options['page'])) {
      if (intval($this->options['page']) < 2) {
	return $this->getQueryString(array('page' => 1));
      } else { 
	return $this->getQueryString(array('page' => ($this->options['page'] - 1)));
      }
    } else {
      return $this->getQueryString(array('page' => 1));
    }
  }

  public function previousPage() {
    return $this->prevPage();
  }
  
  public function getOptions() {
    return $this->options;
  }
  
  public function getUrlPrefix() {
    return $this->url_prefix;
  }

  // Calls the specified PopShops API, then parses the results into internal data structures. 
  // Parameter $call_type is a string. Valid values are 'products', 'merchants', or 'deals'. 
  // The value of $call_type directly selects which API will be called.
  // Parameter $arguments is an associative array mapping $argument=>$value pairs which will be passed to the API.
  // The values of $arguments must be relevant for the API specified by $call_type
  // Returns nothing.
  public function call($call_type='products', $arguments=array()) {
    $this->start_time = microtime(true);
    $this->logger->info("Setting up to call PopShops $call_type API...");
    if ($this->called) {
      $this->logger->error('Client attempted to call PsApiCall object more than once. Call aborted.');
      return;
    }
    if (! in_array($call_type, array('products', 'merchants', 'deals', 'categories'))) {
      $this->logger->error("Invalid call_type '$call_type' was passed to PsApiCall->call. Call aborted.");
      return;
    }
    $this->call_type = $call_type;
    $this->loadOptionsFromGetParams();
    $this->called = true;
    $this->options = array_merge($this->options, $arguments);
    $formatted_options = array();
    foreach ($this->options as $key=>$value) $formatted_options[] = $key . '=' . urlencode($value);
    $url = 'http://api.popshops.com/v3/' . $call_type . '.json?' . implode('&', $formatted_options);
    $this->logger->info('Request URL: ' . $url);
    $this->logger->info('Sending request...');
    $raw_json = file_get_contents($url);
    $this->response_received_time = microtime(true);
    $this->logger->info('JSON file retrieved');
    $parsed_json = json_decode($raw_json, true);
    $this->logger->info('JSON file decoded');
    if ($parsed_json['status'] == '200') {
      $this->logger->info('API reported status 200 OK');
    } else {
      $this->logger->info('API reported unexpected status: ' . $parsed_json['status'] . '; Message: ' . $parsed_json['message']);
      $this->logger->error('Invalid status. Aborting call. Ensure all arguments passed to PsApiCall->get are valid.');
      return;
    }
    $this->logger->info("Processing JSON from $call_type call...");
    $this->processResults($parsed_json);
    $this->logger->info('JSON processing completed.');
    $this->logger->info('Internal processing time elapsed: ' . (string) (microtime(true) - $this->response_received_time) . 's');
    $this->logger->info('Total call time elapsed: ' . (string) (microtime(true) - $this->start_time) . 's');
  }

  public function getCategoryTree() {
    if (isset($this->category_tree)) {
      return $this->category_tree;
    }
  }
  // Retrieves an array of the given type of resource. Accepts plural $resource
  public function resource($resource) {
    $resource = strtolower($resource);
    if (isset($this->{$resource})) {
      return array_values($this->{$resource});
    } else {
      return array();
    }
  }

  // Retrieves an individual resource by its id. Accepts plural $resource
  public function resourceById($resource, $id) {
    if (array_key_exists( $id, $this->{$resource})) {
      return $this->{$resource}[$id];
    } else {
      //return new PsApiDummy($this, $resource . " with id= $id is not present in PsApiCall results");
      return NULL;
    }
  }
  
  public function getProduct($id) {
    return $this->resourceById('products', $id);
  }
  
  public function getBrand($id) {
    return $this->resourceById('brands', $id);
  }
  
  public function getOffer($id) {
    return $this->resourceById('offers', $id);
  }
  
  public function getDeal($id) {
    return $this->resourceById('deals', $id);
  }
  
  public function getMerchant($id) {
    return $this->resourceById('merchants', $id);
  }
  
  public function getCategory($id) {
    return $this->resourceById('categories', $id);
  }
  
  public function getDealType($id) {
    return $this->resourceById('deal_types', $id);
  }
  
  public function getMerchantType($id) {
    return $this->resourceById('merchant_types', $id);
  }
  
  public function getCountry($id) {
    return $this->resourceById('countries', $id);
  }
  
  public function getProducts() {
    return $this->resource('products');
  }
  
  public function getBrands() {
    return $this->resource('brands');
  }
  
  public function getOffers() {
    return $this->resource('offers');
  }
  
  public function getDeals() {
    return $this->resource('deals');
  }
  
  public function getMerchants() {
    return $this->resource('merchants');
  }
  
  public function getCategories() {
    return $this->resource('categories');
  }
  
  public function getCountries() {
    return $this->resource('countries');
  }
  
  public function getDealTypes() {
    return $this->resource('deal_types');
  }
  
  public function getMerchantTypes() {
    return $this->resource('merchant_types');
  }

  private function processObjectJson($json, $class, $resource_name) {
    if (isset($json)) {
      foreach ($json as $object) {
        $this->logger->info('Internalizing ' . substr($resource_name, 0, strlen($resource_name) - 1) . ' with ID=' . (string) $object['id']);
        $this->internalize($object, (new $class($this)), $this->{$resource_name});
      }
    }
  }

  private function processProductsJson($products_json) {
    foreach ($products_json as $product) {
      $this->logger->info('Internalizing product with ID=' . (string) $product['id']);
      $this->internalizeProduct($product);
    }
  }

  private function processDealsJson($json) {
    $this->processObjectJson($json, 'PsApiDeal', 'deals');
  }

  private function processMerchantsJson($json) {
    $this->processObjectJson($json, 'PsApiMerchant', 'merchants');
  }

  private function processBrandsJson($json) {
    $this->processObjectJson($json, 'PsApiBrand', 'brands');
  }

  private function processCategoriesJson($json) {
    $this->processObjectJson($json, 'PsApiCategory', 'categories');
  }

  private function processDealTypesJson($json) {
    $this->processObjectJson($json, 'PsApiDealType', 'deal_types');
  }

  private function processMerchantTypesJson($json) {
    $this->processObjectJson($json, 'PsApiMerchantType', 'merchant_types');
  }

  private function processCountriesJson($json) {
    $this->processObjectJson($json, 'PsApiCountry', 'countries');
  }
  
  private function buildCategoryTree($json) {
    $node = new PsApiCategoryTree($json['name'], $json['id']);
    if (array_key_exists('leaf', $json)) {
      return $node;
    } else {
      foreach($json['categories']['category'] as $cat) {
	$node->addChild($this->buildCategoryTree($cat));
      }
      return $node;
    }
  }
  
  public function getResultsCount() {
    return (int) $this->results_count;
  }

  private function processResults($json) {
    if (isset($json['results'])) {
      if ($this->call_type === 'products') $this->results_count = (int) $json['results']['products']['count'];
      if ($this->call_type === 'merchants') $this->results_count = (int) $json['results']['merchants']['count'];
      if ($this->call_type === 'deals') $this->results_count = (int) $json['results']['deals']['count'];
      if (isset($json['results']['products']))
        $this->processProductsJson($json['results']['products']['product']);
      if (isset($json['results']['merchants']))
        $this->processMerchantsJson($json['results']['merchants']['merchant']);
      if (isset($json['results']['deals']))
        $this->processDealsJson($json['results']['deals']['deal']);
      if (isset($json['results']['categories'])) {
	$this->logger->info('Building category tree from parsed JSON...');
	$this->category_tree = $this->buildCategoryTree($json['results']['categories']['category']['0']);
      }
    }
    if (isset($json['resources'])) {
      if (isset($json['resources']['merchants']))
        $this->processMerchantsJson($json['resources']['merchants']['merchant']);
      if (isset($json['resources']['brands']))
        $this->processBrandsJson($json['resources']['brands']['brand']);
      if (isset($json['resources']['categories'])) {
        if (isset($json['resources']['categories']['matches'])) // Load from matches, if it exists
          $this->processCategoriesJson($json['resources']['categories']['matches']['category']);
        if (isset($json['resources']['categories']['context'])) // Load from context, if it exists
          $this->processCategoriesJson($json['resources']['categories']['context']['category']);
      }
      if (isset($json['resources']['deal_types']))
        $this->processDealTypesJson($json['resources']['deal_types']['deal_type']);
      if (isset($json['resources']['countries']))
        $this->processCountriesJson($json['resources']['countries']['country']);
      if (isset($json['resources']['merchant_types']))
        $this->processMerchantTypesJson($json['resources']['merchant_types']['merchant_type']);
      if (isset($json['resources']['deal_types']))
        $this->processDealTypesJson($json['resources']['deal_types']['deal_type']);
    }
  }

  // Takes the $json, puts its attributes and values into $object, and inserts it into $insert_into, keyed by $object's $json derived id
  private function internalize($json, $object, & $insert_into) {
    foreach ($json as $attribute=>$value) {
      $object->setAttr($attribute, $value);
    }
    $insert_into[$object->attr('id')] = $object;
  }

  // Takes a chunk of decoded JSON representing a single Product (and any included offers)
  // Turns the JSON into a Product object, and appends it to the internal $products array, then turns any included Offer objects, and appends them to the internal $offers array
  private function internalizeProduct($product_json) {
    $tmp = new PsApiProduct($this);
    foreach ($product_json as $attribute=>$value) {
      switch ($attribute) { // This switch is meant to allow processing of special-case attributes
        case 'offers':
	  $offers_array = $value['offer'];
	  foreach ($offers_array as $offer) {
	    $this->internalize($offer, (new PsApiOffer($this)), $this->offers);
	    $this->offers[$offer['id']]->setProduct($tmp); // Set the internalized offer's parent product to the currently internalized product
	    $tmp->addOffer($this->offers[$offer['id']]); // Add the offer we just internalized to the new (currently being internalized) product
	  }
	  break;
        default: // If there's no special case processing to do
	  if (is_numeric($value) or is_string($value)) { // Don't include any arrays or attributes that aren't just strings or numbers
	    $tmp->setAttr($attribute, $value);
	  }
      }
    }
    $this->products[$tmp->attr('id')] = $tmp;
  }
}

abstract class PsApiResource {
  
  protected $attributes;
  protected $reference;
  
  public function __construct($reference) {
    $this->reference = $reference;
    $this->attributes = array();
  }

  // Retrieves and returns the attribute specified (attributes are dumb fields, such as names and ids)
  public function attr($attribute) {
    if (array_key_exists($attribute, $this->attributes)) {
      return $this->attributes[$attribute];
    } else {
      //return 'PopShops API Error: Invalid attribute passed to ' . get_class($this) . '->attr: ' . $attribute;
      return '';
    }
  }

  // Sets the given attribute to the given value. Should not be used by end-users of the PsApiCall library
  public function setAttr($attribute, $value) {
    $this->attributes[$attribute] = $value;
  }

  // Must be implemented by extended classes. Retrieves the specified resource (or array of resources) and returns it
  abstract public function resource($resource);
}

class PsApiProduct extends PsApiResource {

  private $offers;

  public function __construct($reference) {
    parent::__construct($reference);
    $this->offers = array();
  }

  public function addOffer($offer) { // This is a special case method, due to the strange way that the API returns offers (nested inside of products)
    $this->offers[] = $offer;
  }

  public function largestImageUrl() {
    if (array_key_exists('image_url_large', $this->attributes)) {
      return $this->attributes['image_url_large'];
    } else if (array_key_exists('image_url_medium', $this->attributes)) {
      return $this->attributes['image_url_medium'];
    } else if (array_key_exists('image_url_small', $this->attributes)) {
      return $this->attributes['image_url_small'];
    } else {
      return 'No image url provided for product with ID=' . $this->attributes['id'];
    }
  }

  public function smallestImageUrl() {
    if (array_key_exists('image_url_small', $this->attributes)) {
      return $this->attributes['image_url_small'];
    } else if (array_key_exists('image_url_medium', $this->attributes)) {
      return $this->attributes['image_url_medium'];
    } else if (array_key_exists('image_url_large', $this->attributes)) {
      return $this->attributes['image_url_large'];
    } else {
      return 'No image url provided for product with ID=' . $this->attributes['id'];
    }
  }

  // Retrieves the resource specified (resources are objects or arrays of objects somehow connected to this object)
  public function resource($resource) {
    // If the resource has already been computed and cached, just use it. Otherwise, compute and cache it somewhere.
    // Big case statement for each possible type of resource
    // Likely going to be using $this->reference a lot
    switch ($resource) {
      case 'offers':
        return $this->offers; // Special case... No caching because of how offers are nested inside products
      case 'category':
        return $this->reference->resourceById('categories', $this->attr('category'));
      case 'brand':
        return $this->reference->resourceById('brands', $this->attr('brand'));
    }
  }

  public function getBrandId() {
    return $this->attr('brand');
  }
  
  public function getCategoryId() {
    return $this->attr('category');
  }
  
  public function getDescription() {
    return $this->attr('description');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getImageUrlLarge() {
    return $this->attr('image_url_large');
  }
  
  public function getImageUrlMedium() {
    return $this->attr('image_url_medium');
  }
  
  public function getImageUrlSmall() {
    return $this->attr('image_url_small');
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getOfferCount() {
    return $this->attr('offer_count');
  }
  
  public function getPriceMax() {
    return $this->attr('price_max');
  }
  
  public function getPriceMin() {
    return $this->attr('price_min');
  }
  
  public function getBrand() {
    return $this->resource('brand');
  }
  
  public function getCategory() {
    return $this->resource('category');
  }
  
  public function getOffers() {
    return $this->resource('offers');
  }
}

class PsApiMerchant extends PsApiResource {

  private $offers;     // An array of offers from this merchant, if it's been cached already.
  private $deals;      // An array of deals from this merchant, if it's been cached already

  // Retrieves the resource specified (resources are objects or arrays of objects somehow connected to this object)
  public function resource($resource) {
    // If the resource has already been computed and cached, just use it. Otherwise, compute and cache it somewhere.
    // Big case statement for each possible type of resource
    // Likely going to be using $this->reference a lot
    switch ($resource) {
    case 'offers':
      if (isset($this->offers)) {
	return $this->offers;
      } else {
	$this->offers = array();
	foreach ($this->reference->resource('offers') as $offer) {
	  if ($offer->attr('merchant') == $this->attr('id')) {
	    $this->offers[] = $offer;
	  }
	}
	return $this->offers;
      }
    case 'deals':
      if (isset($this->deals)) {
	return $this->deals;
      } else {
	$this->deals = array();
	foreach ($this->reference->resource('deals') as $deal) {
	  if ($deal->attr('merchant') == $this->attr('id')) {
	    $this->deals[] = $deal;
	  }
	}
	return $this->deals;
      }
    case 'merchant_type':
      return $this->reference->resourceById('merchant_types', $this->attr('merchant_type'));
    case 'country':
      return $this->reference->resourceById('countries', $this->attr('country'));
    case 'category':
      return $this->reference->resourceById('categories', $this->attr('category'));
    }
  }
  
  public function getCategoryId() {
    return $this->attr('category');
  }
  
  public function getCountryId() {
    return $this->attr('country');
  }
  
  public function getDealCount() {
    return $this->attr('deal_count');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getLogoUrl() {
    return $this->attr('logo_url');
  }
  
  public function getMerchantTypeId() {
    return $this->attr('merchant_type');
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getNetworkId() {
    return $this->attr('network');
  }
  
  public function getNetworkMerchantId() {
    return $this->attr('network_merchant_id');
  }
  
  public function getProductCount() {
    return $this->attr('product_count');
  }
  
  public function getUrl() {
    return $this->attr('url');
  }
  
  public function getCount() {
    return $this->attr('count');
  }
  
  public function getOffers() {
    return $this->resource('offers');
  }
  
  public function getDeals() {
    return $this->resource('deals');
  }
  
  public function getMerchantType() {
    return $this->resource('merchant_type');
  }
  
  public function getCountry() {
    return $this->resource('country');
  }
  
  public function getCategory() {
    return $this->resource('category');
  }
}

class PsApiDeal extends PsApiResource {
 
  private $deal_types;

  public function resource($resource) {
    switch ($resource) {
    case 'merchant':
      return $this->reference->resourceById('merchants', $this->attr('merchant'));
    case 'deal_types':
      if (isset($this->deal_types)) {
	return $this->deal_types;
      } else {
	$this->deal_types = array();
	$type_ids = explode(',', $this->attr('deal_type'));
	foreach ($type_ids as $type_id) {
	  $this->deal_types[] = $this->reference->resourceById('deal_types', $type_id);
	}
	return $this->deal_types;
      }
    }
  }
  
  public function getCode() {
    return $this->attr('code');
  }
  
  public function getDealTypeId() {
    return $this->attr('deal_type');
  }
  
  public function getDescription() {
    return $this->attr('description');
  }
  
  public function getEndOn() {
    return $this->attr('end_on');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getImageUrl() {
    return $this->attr('image_url');
  }
  
  public function getMerchantId() {
    return $this->attr('merchant');
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getRestrictions() {
    return $this->attr('restrictions');
  }
  
  public function isSiteWide() {
    return $this->attr('site_wide') === 'yes';
  }
  
  public function getStartOn() {
    return $this->attr('start_on');
  }
  
  public function getUrl() {
    return $this->attr('url');
  }
  
  public function getMerchant() {
    return $this->resource('merchant');
  }
  
  public function getDealTypes() {
    return $this->resource('deal_types');
  }
}

class PsApiOffer extends PsApiResource {

  private $product;

  public function setProduct($product) {
    $this->product = $product;
  }

  public function largestImageUrl() {
    if (array_key_exists('image_url_large', $this->attributes)) {
      return $this->attributes['image_url_large'];
    } else if (array_key_exists('image_url_medium', $this->attributes)) {
      return $this->attributes['image_url_medium'];
    } else if (array_key_exists('image_url_small', $this->attributes)) {
      return $this->attributes['image_url_small'];
    } else {
      return 'No image url provided for offer with ID=' . $this->attributes['id'];
    }
  }

  public function smallestImageUrl() {
    if (array_key_exists('image_url_small', $this->attributes)) {
      return $this->attributes['image_url_small'];
    } else if (array_key_exists('image_url_medium', $this->attributes)) {
      return $this->attributes['image_url_medium'];
    } else if (array_key_exists('image_url_large', $this->attributes)) {
      return $this->attributes['image_url_large'];
    } else {
      return 'No image url provided for offer with ID=' . $this->attributes['id'];
    }
  }

  // Retrieves the resource specified (resources are objects or arrays of objects somehow connected to this object)
  public function resource($resource) {
    switch ($resource) {
      case 'product':
        return $this->product;
      case 'merchant':
        return $this->reference->resourceById('merchants', $this->attr('merchant'));
    }
  }
  
  public function getCondition() {
    return $this->attr('condition');
  }
  
  public function getCurrencyIso() {
    return $this->attr('currency_iso');
  }
  
  public function getCurrencySymbol() {
    return $this->attr('currency_symbol');
  }
  
  public function getDescription() {
    return $this->attr('description');
  }
  
  public function getEstimatedShipping() {
    return $this->attr('estimated_shipping');
  }
  
  public function getEstimatedSalesTaxRate() {
    return $this->attr('estimated_sales_tax_rate');
  }
  
  public function getEstimatedSalesTax() {
    return $this->attr('estimated_sales_tax');
  }
  
  public function getEstimatedPriceTotal() {
    return $this->attr('estimated_price_total');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getImageUrlLarge() {
    return $this->attr('image_url_large');
  }
  
  public function getImageUrlMedium() {
    return $this->attr('image_url_medium');
  }
  
  public function getImageUrlSmall() {
    return $this->attr('image_url_small');
  }
  
  public function getMerchantId() {
    return $this->attr('merchant');
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getPercentOff() {
    return $this->attr('percent_off');
  }
  
  public function getPriceMerchant() {
    return $this->attr('price_merchant');
  }
  
  public function getPriceRetail() {
    return $this->attr('price_retail');
  }
  
  public function getSku() {
    return $this->attr('sku');
  }
  
  public function getUrl() {
    return $this->attr('url');
  }
  
  public function getMerchant() {
    return $this->resource('merchant');
  }
  
  public function getProduct() {
    return $this->resource('product');
  }
}

class PsApiBrand extends PsApiResource {

  private $products;

  public function resource($resource) {
    switch($resource) {
    case 'products':
      if (isset($this->products)) {
        return $this->products;
      } else {
        $this->products = array();
        foreach ($this->reference->resource('products') as $product) {
          if ($product->attr('brand') == $this->attr('id')) {
            $this->products[] = $product;
          }
        }
        return $this->products;
      }
    }
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getCount() {
    return $this->attr('count');
  }
  
  public function getProducts() {
    return $this->resource('products');
  }
}

class PsApiCategory extends PsApiResource {

  public function resource($resource) {
    switch($resource) {
    case 'products':
      if (isset($this->products)) {
        return $this->products;
      } else {
        $this->products = array();
        foreach ($this->reference->resource('products') as $product) {
          if (((string) $product->attr('category')) == ((string) $this->attr('id'))) {
            $this->products[] = $product;
          }
        }
        return $this->products;
      }
    }
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getCount() {
    return $this->attr('count');
  }
  
  public function isLeaf() {
    return array_key_exists('leaf', $this->attributes);
  }
  
  public function getProducts() {
    return $this->resource('products');
  }
}

class PsApiCategoryTree {
  
  private $children;
  private $name;
  private $id;
  
  public function __construct($name, $id) {
    $this->name = $name;
    $this->id = $id;
    $this->children = array();
  }
  
  public function addChild($child) {
    $this->children[] = $child;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getChildren() {
    return $this->children;
  }  
}

class PsApiDealType extends PsApiResource {

  private $deals;

  public function resource($resource) {
    switch ($resource) {
    case 'deals':
      if (isset($this->deals)) {
        return $this->deals;
      } else {
        $this->deals = array();
        foreach ($this->reference->resource('deals') as $deal) {
          $type_ids = explode(',', $deal->attr('deal_type'));
          foreach ($type_ids as $type_id) {
            if (((string) $type_id) == ((string) $this->attr('id'))) {
              $this->deals[] = $deal;
            }
          }
        }
        return $this->deals;
      }
    }
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getCount() {
    return $this->attr('count');
  }
  
  public function getDeals() {
    return $this->resource('deals');
  }
}

class PsApiCountry extends PsApiResource {

  private $merchants;

  public function resource($resource) {
    switch($resource) {
    case 'merchants':
      if (isset($this->merchants)) {
        return $this->merchants;
      } else {
        $this->merchants = array();
        foreach ($this->reference->resource('merchants') as $merchant) {
        if (((string) $merchant->attr('country')) == ((string) $this->attr('id'))) {
          $this->merchants[] = $merchant;
        }
      }
        return $this->merchants;
      }
    }
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getCount() {
    return $this->attr('count');
  }
  
  public function getMerchants() {
    return $this->resource('merchants');
  }
}

class PsApiMerchantType extends PsApiResource {

  private $merchants;

  public function resource($resource) {
    switch($resource) {
    case 'merchants':
      if (isset($this->merchants)) {
        return $this->merchants;
      } else {
        $this->merchants = array();
        foreach ($this->reference->resource('merchants') as $merchant) {
        if (((string) $merchant->attr('merchant_type')) == ((string) $this->attr('id'))) {
          $this->merchants[] = $merchant;
        }
      }
        return $this->merchants;
      }
    }
  }
  
  public function getName() {
    return $this->attr('name');
  }
  
  public function getId() {
    return $this->attr('id');
  }
  
  public function getCount() {
    return $this->attr('count');
  }
  
  public function getMerchants() {
    return $this->resource('merchants');
  }
}

// Meant to be returned when an error occurs
class PsApiDummy extends PsApiResource {
  
  private $message;

  public function __construct($reference, $message=null) {
    parent::__construct($reference);
    if (isset($message)) {
      $this->message = $message;
    }
  }

  public function attr($attribute) {
    if (isset($this->message)) {
      return '[' . $this->message . ']';
    } else {
      return '[This element does not exist]';
    }
  }

  public function resource($resource) {
    if (isset($this->message)) {
      return new PsApiDummy($reference, 'Parent object message: ' . $this->message);
    } else {
      return new PsApiDummy($reference);
    }
  }
}

?>