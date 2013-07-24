<?php

  function renderOffer($offer) { ?>
    <tr>
      <td class="span2">
        <img class="merchant-small-image" src="<?=$offer->resource('merchant')->attr('logo_url') ?>">
      </td>
      <td class="span2">
        <h5>$<?= money_format('%i', $offer->attr('price_merchant')) ?></h5>
      </td>
      <td class="span2">
        <?= ucfirst($offer->attr('condition')) ?>
      </td>
      <td class="span2">
        <a href="<?= $offer->attr('url') ?>" rel="nofollow" class="btn">Go to Store</a>
      </td>
    </tr>
  <?php }

  function renderMerchant($merchant) { ?>
    <tr>
      <td class="span2">
        <img class="merchant-small-image" src="<?=$merchant->attr('logo_url')?>">
      </dtd>
      <td class="span2">
        <a href="merchant.php?merchant=<?=$merchant->attr('id')?>">
          <?=$merchant->attr('name')?>
        </a>
      </td>
      <td class="span2">
        <?=$merchant->attr('deal_count')?>
      </td>
      <td class="span2">
        <?=$merchant->attr('product_count')?>
      </td>
    </tr>
  <?php }

  function renderProduct($product) {
    $description_cutoff = 200; ?>
    <div class="row">
      <div class="span2">
        <a href=product.php?product="<?= $product->attr('id')?>">
          <img class="search-product-image" src="<?= $product->largestImageUrl() ?>">
        </a>
      </div>
      <div class="span2">
        <a href="product.php?product=<?= $product->attr('id') ?>"><?= $product->attr('name') ?></a>
      </div>
      <div class="span3 product-description more-less">
        <?php
          if (strlen($product->attr('description')) > $description_cutoff) { ?>
            <div class="less">
              <?= substr(htmlentities($product->attr('description')), 0, $description_cutoff) ?>...
              <a href="#" class="read-more">Read more</a>
            </div>
            <div class="more" style="display:none;">
              <?= $product->attr('description') ?>
              <a href="#" class="read-less">Read less</a>
            </div>
          <?php } else { ?>
            <?= htmlentities($product->attr('description')) ?>
          <?php } ?>
      </div>
      <div class="span2">
        <?php if ($product->attr('price_min') == $product->attr('price_max')) { ?>
          $<?= money_format('%i', $product->attr('price_min')) ?>
        <?php } else { ?>
          $<?= money_format('%i', $product->attr('price_min')) ?><i>-</i> $<?= money_format('%i', $product->attr('price_max')) ?>
        <?php } ?>
        <br><br>
        <a href="product.php?product=<?= $product->attr('id') ?>">
          Offers available: <?= $product->attr('offer_count') ?>
        </a>
      </div>
    </div>
    <hr />
  <?php }

?>