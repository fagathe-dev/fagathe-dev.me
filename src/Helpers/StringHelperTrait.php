<?php

namespace App\Helpers;

trait StringHelperTrait
{

    /**
     * Skip accents in string
     * @param string $str
     * @param string $charset
     * @return string
     */
    public function skipAccents(string $str, string $charset = 'utf-8'): string
    {
        $str    = trim($str);
        $str    = htmlentities($str, ENT_NOQUOTES, $charset);

        $str    = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str    = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str    = preg_replace('#&[^;]+;#', '', $str);
        $str    = preg_replace('/[^A-Za-z0-9\-]/', ' ', $str);

        return $str;
    }

}
