<?php

namespace Librinfo\BaseEntitiesBundle\SearchAnalyser;

use Behat\Transliterator\Transliterator;

class SearchAnalyser
{
    /**
     * @param string $text
     * @return array
     */
    public static function analyse($text)
    {
        // to lowercase
        $text = mb_strtolower(trim($text), 'utf-8');

        // remove accents
        $text = Transliterator::unaccent($text);

        // considering very special chars as spaces
        $text = str_replace(array(
          '@',
          '.',',','¿',
          '♠','♣','♥','♦',
          '-','+',
          '←','↑','→','↓',
          "'",'’','´',
          '●','•',
          '¼','½','¾',
          '“', '”', '„',
          '°','™','©','®',
          '³','²',
        ),' ',$text);

        // remove multiple spaces
        $text = preg_replace('/\s+/', ' ',$text);

        return explode(' ', $text);
    }
}
