<?php

function println($s) {
  print($s . "\n");
}

function renderProduct($product) {
  $description_cutoff = 200;
  println('<div class="row">');
  println('  <div class="span2">');
  println('    <a href=product.php?product=' . $product->attr('id') . '>');
  println('      <img style="width:100%;max-width:200px;max-height:150px;" src=' . $product->largestImageUrl() . '>');
  println('    </a>');
  println('  </div>');
  println('  <div class="span2">');
  println('    <a href="product.php?product=' . $product->attr('id') . '">');
  println('      ' . $product->attr('name'));
  println('    </a>');
  println('  </div>');
  println('  <div class="span3" id="product-desc-' . $product->attr('id') . '">');
  if (strlen($product->attr('description')) > $description_cutoff) {
    println('    ' . substr(htmlentities($product->attr('description')), 0, $description_cutoff) . '...');
    println('<script language="javascript">function product_desc_show_' . $product->attr('id') .
	    '() { document.getElementById("product-desc-' . $product->attr('id') . '").innerHTML = "' . htmlentities($product->attr('description')) . 
	    '"; return false; }</script>');
    println('<br><a href="#!" onclick="product_desc_show_' . $product->attr('id') . '()">Read more</a>');
  } else {
    println('    ' . htmlentities($product->attr('description')));
  }
  println('  </div>');
  println('  <div class="span2">');
  if ($product->attr('price_min') == $product->attr('price_max')) {
    println('    $' . money_format('%i', $product->attr('price_min')));
  } else {
    println('    $' . money_format('%i', $product->attr('price_min')) . ' <i>to</i> $' .
            money_format('%i', $product->attr('price_max')));
  }
  println('    <br><br>');
  println('    <a href="product.php?product=' . $product->attr('id') . '">');
  println('      Offers available: ' . $product->attr('offer_count'));
  println('    </a>');
  println('  </div>');
  println('</div>');
  println('<hr>');
}
?>
