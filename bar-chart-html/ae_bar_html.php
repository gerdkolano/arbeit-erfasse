<?php 
function ae_bar_html(&$values, $height=400, $color='black', $width='')
{
    $max = -1;
    
    foreach($values as $k=>$v)
        if (abs($v) > $max)
            $max = abs($v);
    
    if ($max != 0)
        $kf = $height / $max;
    else
        $kf = 0;
    
    if ($width != '')
        $width = "width: {$width}px; ";

    $out = "<tr style='vertical-align: bottom;'>\n";    
    foreach($values as $k=>$v)
    {
        $bar_h = abs(round($v*$kf));
        $out .= "<td style='{$width}padding-top: 0; margin-top: 0;";
        $out .= " height: {$height}px; border-bottom: {$bar_h}px solid {$color}; text-align: center;'>\n";
        $out .= "{$v}</td>\n";
    }
    $out .= "</tr>\n";
    
    
    $out .= "<tr>\n";    

    foreach($values as $k=>$v)
        $out .= "<td style='text-align: center'>{$k}</td>\n";
        
    $out .= "</tr>\n";
    return $out;
}

?>
