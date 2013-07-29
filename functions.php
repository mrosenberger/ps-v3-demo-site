<?php

  function renderOffer($offer) { ?>
    <tr>
      <td class="span2">
        <img class="merchant-small-image" src="<?=$offer->getMerchant()->getLogoUrl() ?>">
      </td>
      <td class="span2">
        <h5>$<?= money_format('%i', $offer->getPriceMerchant()) ?></h5>
      </td>
      <td class="span2">
        <?= ucfirst($offer->getCondition()) ?>
      </td>
      <td class="span2">
        <a href="<?= $offer->getUrl() ?>" rel="nofollow" class="btn">Go to Store</a>
      </td>
    </tr>
  <?php }

  function renderMerchant($merchant) { ?>
    <tr>
      <td class="span2">
        <img class="merchant-small-image" src="<?=$merchant->getLogoUrl()?>">
      </dtd>
      <td class="span3">
        <a href="search.php?psapi_merchant=<?= $merchant->getId() ?>">
          <?=$merchant->getName()?>
        </a>
      </td>
      <td class="span2" style="text-align:left">
        <?=$merchant->getDealCount()?>
      </td>
      <td class="span2">
        <?=$merchant->getProductCount()?>
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
  
  function generateBootstrapPagination($api, $num_cells=5, $center=true) {
    $current = 1;
    $total = (int) $api->getResultsCount();
    $per_page = 20;
    if ($api->hasParameter('results_per_page')) { $per_page = (int) $api->getParameter('results_per_page'); }
    if ($api->hasParameter('page')) { $current = (int) $api->getParameterValue('page'); }
    $pages = (int) ($total / $per_page);
    $half = (int) ($num_cells / 2);
    $min = 0;
    if ($current < ($half + 1)) {
      $min = 1;
    } else {
      $min = $current - $half;
    }?>
    <div class="pagination <?php if ($center) { print('pagination-centered'); } ?>">
      <ul>
        <li><a href="<?= $api->paginate(1) ?>">&laquo; First</a></li>
        <?php for ($i=$min; $i < $min + $num_cells; $i += 1) {
          if ($i <= $pages) { ?>
            <li <?php if ($i === $current) { print('class="active"'); } ?>>
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
?>