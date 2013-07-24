<?php
function render_merchant($merchant) {
    println('<div class="row" style="padding-top:10px;">');
    println('  <div class="span2">');
    println('    <img style="max-height:31px" src="' . $merchant->attr('logo_url') . '">');
    println('  </div>');
    println('  <div class="span2">');
    println('    <a href="merchant.php?merchant=' . $merchant->attr('id') . '">');
    println('      ' . $merchant->attr('name'));
    println('    </a>');
    println('  </div>');
    println('  <div class="span2">');
    println('    ' . $merchant->attr('deal_count'));
    println('  </div>');
    println('  <div class="span2">');
    println('    ' . $merchant->attr('product_count'));
    println('  </div>');
    println('</div>');
}