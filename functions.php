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
?>