<?php
namespace model\web\Jobs\App\Jobs;

/**
 * 
 * Utilidades para PHP CLI
 * 
 * @author Smartclip
 *
 */
class CLI
{
    
    const COLOR_START      = "\033[";
    const COLOR_END        = "m";   
    const DISABLE_COLOR    = "\033[0m"; 
    
    const FG_COLORS = [
        "default"      => "0;39",
        "black"        => "0;30",
        "dark_gray"    => "1;30",
        "blue"         => "0;34",
        "light_blue"   => "1;34",
        "green"        => "0;32",
        "light_green"  => "1;32",
        "cyan"         => "0;36",
        "light_cyan"   => "1;36",
        "red"          => "0;31",
        "light_red"    => "1;31",
        "purple"       => "0;35",
        "light_purple" => "1;35",
        "brown"        => "0;33",
        "yellow"       => "1;33",
        "light_gray"   => "0;37",
        "white"        => "1;37",
    ];
    
    const BG_COLORS = [
        "default"    => "49",
        "black"      => "40",
        "red"        => "41",
        "green"      => "42",
        "yellow"     => "43",
        "blue"       => "44",
        "magenta"    => "45",
        "cyan"       => "46",
        "light_gray" => "47",
    ];
    
    public static function colorStr(String $string, String $fgColor='default', String $bgColor='default') : String
    {
        $colorString  = self::createColor($fgColor, self::FG_COLORS);
        $colorString .= self::createColor($bgColor, self::BG_COLORS);
        $colorString .= $string . self::DISABLE_COLOR; 
        
        return $colorString;
    }
    
    private static function createColor(String $color, Array $colors)
    {
        $color = array_key_exists($color, $colors) ? $colors[$color] : $colors['default'];
        return self::COLOR_START.$color.self::COLOR_END;
    }
    
}