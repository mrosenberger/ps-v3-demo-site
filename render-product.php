<?php

function println($s) {
  print($s . "\n");
}


function renderProduct($product) {
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
  println('    <i>' . substr($product->attr('description'), 0, 150) . '</i>');
  println('  </div>');
  println('  <div class="span2">');
  println('    Offers available: ' . $product->attr('offer_count'));
  println('  </div>');
  println('</div>');
  println('<hr>');
}
?>