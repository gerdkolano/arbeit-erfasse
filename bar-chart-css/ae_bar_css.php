<?php 
function ae_bar_css(&$values, $height=400, $css_prefix='')
{
    $max = -1;

    foreach($values as $k=>$v)
        if (abs($v) > $max)
            $max = abs($v);
    
    if ($max != 0)
        $kf = $height / $max;
    else
        $kf = 0;

    $out = "<tr class='{$css_prefix}barvrow'>\n";    
    foreach($values as $k=>$v)
    {
        $bar_h = abs(round($v*$kf));
        $out .= "<td style='border-bottom-width: {$bar_h}px'>{$v}</td>\n";
    }
    $out .= "</tr>\n";
    
    
    $out .= "<tr class='{$css_prefix}bartrow'>\n";    

    foreach($values as $k=>$v)
        $out .= "<td>{$k}</td>\n";
        
    $out .= "</tr>\n";
    return $out;
}

?>
