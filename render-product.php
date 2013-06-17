<?php

function println($s) {
  print($s . "\n");
}


function renderProduct($product) {
  println('<div class="row">');
  println('  <div class="span2 offset1">');
  println('    <img style="width:100%;max-width:200px" src=' . $product->largestImageUrl() . '>');
  println('  </div>');
  println('  <div class="span3">');
  println('    ' . $product->attr('name'));
  println('  </div>');
  println('  <div class="span5">');
  println('    <i>' . $product->attr('description') . '</i>');
  println('  </div>');
  println('  <div class="span2">');
  println('    Offers available: ' . $product->attr('offer_count'));
  println('  </div>');
  println('</div>');
  println('<hr>');
}
?>