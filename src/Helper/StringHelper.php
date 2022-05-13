<?php

namespace App\Helper;

use Doctrine\Common\Collections\Collection;

class StringHelper
{
    public static function extract(
        string $content,
        int $maxCharacters = 100,
        $replacer = '...'
    ) {
        if (strlen($content) > $maxCharacters) {
            return (preg_match('/^(.*)\W.*$/', substr($content, 0, $maxCharacters + 1), $matches)
                ? $matches[1]
                : substr($content, 0, $maxCharacters)) . $replacer;
        }
        return $content;
    }

    /**
     * Convert a string value into a boolean value (true or false)
     * @param string $str The string to convert
     * @return bool
     */
    public static function toBool(string $str)
    {
        if (in_array($str, ['true', '1'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function toString($value)
    {
        if (is_bool($value)) {
            if ($value) {
                return '1';
            }
            return '0';
        }
    }

    /**
     * Transform an array into a string like "I have a dog, a cat and 3 goldfishes in my garden." array.
     * @param array|Collection $array Array of strings, int,... or Collection of objects
     * @param null|string $property If $array is an array of objects, precise the property to get.
     * (null if not an array of objects)
     * @param null|int $limit Max number of items listed. if this number is reached, the list will be trimmed by a '...'
     * @param string $before
     * @param string $after
     * @return string
     */
    public static function arrayToSentence($array, ?string $property = null, ?int $limit = null, string $before = '', string $after = ''): string
    {
        $list = '';

        $items = count($array);
        if ($items < 1) {
            return '';
        }
        for ($i = 0; $i <= $limit; $i++) {
            $item = $array[$i];
            if ($property !== null) {
                $getterFn = 'get' . ucfirst($property);
                $item = $item->$getterFn();
            }
            if ($i < $items - 2) {
                $list .= $item . ', ';
            } elseif ($i < $items - 1) {
                $list .= $item . ' and ';
            } else {
                $list .= $item;
            }
        }

        return $before . $list . $after;
    }

    public static function replaceAllBeetweenTags(string $content, string $tag, string $search, string $replace)
    {
        //this preg is searching for tags and text inside it
        //and then change all first words to upper
        $filteredContent = preg_replace_callback(
            '#(<'.$tag.'.*?>)(.*?)(</'.$tag.'>)#',
            function ($matches) use ($search, $replace) {
                //this preg is searching for last letters in words and changing it to upper
                $t = str_replace($search, $replace, $matches[2]);
                return $matches[1] . $t . $matches[3];
            },
            $content
        );
        return $filteredContent;
    }
}
