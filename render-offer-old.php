<?php
function render_offer($offer) {
    println('<div class="row">');
    println('  <div class="span2">');
    println('    <img style="max-height:31px" src=' . $offer->resource('merchant')->attr('logo_url') . '>');
    println('  </div>');
    println('  <div class="span2"><h5>');
    println('   $' . money_format('%i', $offer->attr('price_merchant')));
    println('  </h5></div>');
    println('  <div class="span2">');
    println('    ' . ucfirst($offer->attr('condition')));
    println('  </div>');
    println('  <div class="span2">');
    println("    <button type=\"submit\" class=\"btn\" onClick=\"location.href='" . $offer->attr('url')."';\">Go to Store</button>");
    println('  </div>');
    println('</div>');
    println('<br>');
}