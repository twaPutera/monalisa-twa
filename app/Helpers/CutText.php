<?php

namespace App\Helpers;

/**
 * Created by Deyan Ardi 2022.
 *  Cut Text By Lenght & By Underscore
 */
class CutText
{
    public static function cut(string $text, string $length)
    {
        if ($text != null) {
            $truncated = substr($text, 0, $length) . '...';
        } else {
            $truncated = '...';
        }

        return $truncated;
    }

    public static function cutUnderscore(string $text = null)
    {
        $cuts = explode('_', $text);
        $result = '';
        if ($text != null) {
            foreach ($cuts as $index => $item) {
                if ($index == 0) {
                    $result = ucWords($item);
                } else {
                    if ($item != '') {
                        $result .= ' ' . ucWords($item);
                    } else {
                        $result .= ucWords($item);
                    }
                }
            }
        }
        return $result;
    }
}
