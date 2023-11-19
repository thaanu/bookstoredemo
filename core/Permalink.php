<?php
/**
 * Permalink Class
 * @author Ahmed Shan (@thaanu16)
 */
namespace Heliumframework;

class Permalink
{

    public static function web( $url )
    {
        return self::url('', $url);
    }

    public static function cpanel( $url )
    {
        return self::url(_PREFIX_CPANEL, $url);
    }

    public static function api( $url )
    {
        return self::url(_PREFIX_API, $url);
    }

    private static function url($panel, $url)
    {
        $url = self::dotNotation($url);
        return ( empty($panel) ? '' : '/' . $panel) . '/' . $url;
    }

    private static function dotNotation( $url )
    {
        $escapes = ['png', 'jpeg', 'jpg'];
        $url = str_replace('.', '/', $url);
        foreach ( $escapes as $escape ) {
            $url = str_replace("/$escape", ".$escape", $url);
        }
        return $url;
    }

}

