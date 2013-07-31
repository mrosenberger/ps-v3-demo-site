<?php

  function renderOffer($offer) { ?>
    <tr>
      <td class="span2">
        <img class="merchant-small-image" src="<?=$offer->getMerchant()->getLogoUrl() ?>">
      </td>
      <td class="span2">
        <span class="offer-price-merchant">$<?= money_format('%i', $offer->getPriceMerchant()) ?></span>
      </td>
      <td class="span2">
        <span class="offer-condition"><?= ucfirst($offer->getCondition()) ?></span>
      </td>
      <td class="span2">
        <a href="<?= $offer->getUrl() ?>" rel="nofollow" class="btn offer-store-button">Go to Store</a>
      </td>
    </tr>
  <?php }

  function renderMerchant($merchant) { ?>
    <tr>
      <td class="span2">
        <img class="merchant-small-image" src="<?=$merchant->getLogoUrl()?>">
      </td>
      <td class="span4">
        <a href="search.php?psapi_merchant=<?= $merchant->getId() ?>">
          <?=$merchant->getName()?>
        </a>
      </td>
      <td class="span2">
        <?php
          $count = $merchant->getProductCount();
          if ($count > 0) { ?>
            <a href="search.php?psapi_merchant=<?= $merchant->getId() ?>">
              <?=$merchant->getProductCount()?>
            </a>
            <?php
          } else {
            print($merchant->getProductCount());
          }
        ?>
      </td>
      <td class="span2">
        <?php
          $count = $merchant->getDealCount();
          if ($count > 0) { ?>
            <a href="coupons.php?psapi_merchant=<?= $merchant->getId() ?>">
              <?=$merchant->getDealCount()?>
            </a>
            <?php
          } else {
            print($merchant->getDealCount());
          }
        ?>
      </td>
    </tr>
  <?php }

  function renderProduct($product) {
    $description_cutoff = 200; ?>
    <div class="row">
      <div class="span2">
        <a href="product.php?psapi_product=<?= $product->getId()?>">
          <img class="search-product-image" src="<?= $product->largestImageUrl() ?>">
        </a>
      </div>
      <div class="span2">
        <a href="product.php?psapi_product=<?= $product->getId() ?>"><?= $product->getName() ?></a>
      </div>
      <div class="span3 product-description more-less">
        <?php
          if (strlen($product->getDescription()) > $description_cutoff) { ?>
            <div class="less">
              <?= substr(htmlentities($product->getDescription()), 0, $description_cutoff) ?>...
              <a href="#" class="read-more">Read more</a>
            </div>
            <div class="more" style="display:none;">
              <?= $product->getDescription() ?>
              <a href="#" class="read-less">Read less</a>
            </div>
          <?php } else { ?>
            <?= htmlentities($product->getDescription()) ?>
          <?php } ?>
      </div>
      <div class="span2">
        <?php if ($product->getPriceMin() == $product->getPriceMax()) { ?>
          $<?= money_format('%i', $product->getPriceMin()) ?>
        <?php } else { ?>
          $<?= money_format('%i', $product->getPriceMin()) ?><i>-</i> $<?= money_format('%i', $product->getPriceMax()) ?>
        <?php } ?>
        <br><br>
        <a href="product.php?psapi_product=<?= $product->getId() ?>">
          Offers available: <?= $product->getOfferCount() ?>
        </a>
      </div>
    </div>
    <hr />
  <?php }
  
  function renderDeal($deal) {
    $description_cutoff = 200; ?>
    <div class="row">
      <div class="span2">
        <a href="<?= $deal->getUrl() ?>">
          <?php if ($deal->getMerchant()) { ?>
            <img class="merchant-small-image" src="<?= $deal->getMerchant()->getLogoUrl() ?>">
          <?php } ?>
        </a>
      </div>
      <div class="span4 deal-name">
        <a href="<?= $deal->getUrl() ?>"><?= $deal->getName() ?></a>
      </div>
      <div class="span3">
        <?php if ($deal->getStartOn() != '') { ?>
          <span class="deal-start-label">Valid from: </span><span class="deal-start-value"><?= $deal->getStartOn() ?></span><br />
        <?php }
        if ($deal->getEndOn() != '') { ?>
          <span class="deal-end-label">Good through: </span><span class="deal-end-value"><?= $deal->getEndOn() ?></span><br />
        <?php }
        if (($deal->getCode() != '') and !in_array(strtolower($deal->getCode()),
                                                   array('none', 'no code required', 'n/a', 'no code needed', 'no coupon code'))) { ?>
          <span class="deal-code-label">Coupon code: </span><span class="deal-code-value"><?= $deal->getCode() ?></span>
        <?php } ?>
      </div>
    </div>
    <hr />
  <?php }
  
  function generateBootstrapPagination($api, $num_cells=8, $center=true) {
    $current = 1;
    $total = (int) $api->getResultsCount();
    $per_page = 20;
    if ($api->hasParameter('results_per_page')) { $per_page = (int) $api->getParameterValue('results_per_page'); }
    if ($api->hasParameter('page')) { $current = (int) $api->getParameterValue('page'); }
    $pages = (int) ($total / $per_page);
    if ($total % $per_page != 0.0) {
      $pages += 1;
    }
    if ($pages < 2) { ?>
      <div class="pagination generated-pagination <?php if ($center) { print('pagination-centered'); } ?>">
        <ul>
          <li class="active">
            <a href="#">
              Page 1 of 1
            </a>
          </li>
        </ul>
      </div>
      <?php
      return;
    }
    $half = (int) ($num_cells / 2);
    $min = 0;
    if ($current < ($half + 1)) {
      $min = 1;
    } else {
      $min = $current - $half;
    }?>
    <div class="pagination generated-pagination <?php if ($center) { print('pagination-centered'); } ?>">
      <ul>
        <li><a href="<?= $api->paginate(1) ?>">&laquo; First</a></li>
        <?php for ($i=$min; $i < $min + $num_cells; $i += 1) {
          if ($i <= $pages) { ?>
            <li<?php if ($i === $current) { print(' class="active"'); } ?>>
              <a href="<?= $api->paginate($i) ?>">
                <?= $i ?>
              </a>
            </li>
          <?php }
        }?>
        <li><a href="<?= $api->paginate($pages) ?>">Last &raquo;</a></li>
      </ul>
    </div><?php
  }
  
  function generateHiddenParameters($api, $omit=array()) {
    $omit[] = 'catalog';
    $omit[] = 'account';
    foreach ($api->getOptions() as $option=>$value) {
      if (! in_array($option, $omit)) { ?>
        <input type="hidden" name="<?= $api->getUrlPrefix() . $option ?>" value="<?= $value ?>">
      <?php }
    }
  }
  
  function getCategoryIcon($s) {
    switch ($s) {
      case "32194": // Arts & Crafts
        return "icon-pencil";
      case "2000": // Automotive Parts & Vehicles
        return "icon-road";
      case "15000": // Baby & Family
        return "icon-home";
      case "3000": // Clothing & Accessories
        return "icon-briefcase";
      case "5000": // Computers & Software
        return "icon-off";
      case "7000": // Electronics
        return "icon-camera";
      case "10000": // Events & Tickets
        return "icon-tags";
      case "12000": // Food, Flowers & Gifts
        return "icon-gift";
      case "13000": // Health & Beauty
        return "icon-eye-open";
      case "16000": // Home & Garden
        return "icon-home";
      case "9000": // Mature & Adult
        return "icon-heart";
      case "21000": // Media
        return "icon-film";
      case "22000": // Musical Instruments
        return "icon-music";
      case "24000": // Office & Professional Supplies
        return "icon-envelope";
      case "23000": // Pets & Animal Supplies
        return "icon-leaf";
      case "25000": // Shoes & Accessories
        return "icon-shopping-cart";
      case "32346": // Specialty & Novelty
        return "icon-star";
      case "27000": // Sports & Outdoor Activities
        return "icon-globe";
      case "31000": // Toys, Games & Hobbies
        return "icon-bell";
      case "32345": // Travel
        return "icon-plane";
      case "8400": // Video Games, Consoles & Accessories
        return "icon-hdd";
      case "9100": // Weapons
        return "icon-screenshot";
      default:
        return "icon-certificate";
    }
  }
?>