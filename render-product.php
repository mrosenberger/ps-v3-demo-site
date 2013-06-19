<?php

function println($s) {
  print($s . "\n");
}


function renderProduct($product) {
  $description_cutoff = 200;
  println('<div class="row">');
  println('  <div class="span2">');
  println('    <a href="' . $product->largestImageUrl() . '">');
  println('      <img style="width:100%;max-width:200px" src=' . $product->largestImageUrl() . '>');
  println('    </a>');
  println('  </div>');
  println('  <div class="span2">');
  println('    <a href="product.php?product="' . $product->attr('id') . '">');
  println('      ' . $product->attr('name'));
  println('    </a>');
  println('  </div>');
  println('  <div class="span3">');
  if (strlen($product->attr('description')) > $description_cutoff) {
    println('    ' . substr($product->attr('description'), 0, $description_cutoff) . '...');
    println('    <br><a href="$product.php?product="' . $product->attr('id') . '">Read more</a>');
  } else {
    println('    ' . $product->attr('description'));
  }
  println('  </div>');
  println('  <div class="span2">');
  if ($product->attr('price_min') == $product->attr('price_max')) {
    println('    $' . $product->attr('price_min'));
  } else {
    println('    $' . $product->attr('price_min') . ' to $' . $product->attr('price_max'));
  }
  println('    <br>');
  println('    Offers available: ' . $product->attr('offer_count'));
  println('  </div>');
  println('</div>');
  println('<hr>');
}
?>