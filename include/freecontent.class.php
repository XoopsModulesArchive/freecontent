<?php

// ------------------------------------------------------------------------- //
//                           FreeContent for Xoops                           //
//                              Version:  3.x                                //
// ------------------------------------------------------------------------- //
// Author: Wang Jue (alias wjue)                                             //
// Purpose: Module to wrap html or php-content into nice Xoops design.       //
//          Digest Headlines (WebDigest) from any webpage.                   //
// email: wjue@wjue.org                                                      //
// URLs: http://www.wjue.org,  http://www.guanxiCRM.com                      //
//---------------------------------------------------------------------------//
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//---------------------------------------------------------------------------//
//***************************************************************************//
//* Caution: FreeContent v3.x is NOT compatible with Xoops 1.3.x            *//
//*          Use FreeContent 1.2 for that purpose                           *//
//***************************************************************************//
//The compatibility Xoops wrapping method uses v1.0 of Stefan "SibSerag" Oese//

class FreeContent
{
    /**
     *  for furture extension
     */
    public function __construct()
    {
    }

    /**
     * If magic_quotes_gpc is in use, run stripslashes() on $var.
     *
     *
     * @param  string $var  The string to un-quote, if necessary.
     *
     * @return string, minus any magic quotes.
     */
    public function dispelMagicQuotes(&$var)
    {
        static $magic_quotes;

        if (!isset($magic_quotes)) {
            $magic_quotes = get_magic_quotes_gpc();
        }

        if ($magic_quotes) {
            if (!is_array($var)) {
                $var = stripslashes($var);
            } else {
                array_walk($var, ['Horde', 'dispelMagicQuotes']);
            }
        }

        return $var;
    }

    /**
     * Get a form variable from GET or POST data, stripped of magic
     * quotes if necessary. If the variable is somehow set in both the
     * GET data and the POST data, the value from the POST data will
     * be returned and the GET value will be ignored.
     *
     *
     * @param string $var       The name of the form variable to look for.
     * @param null   $default   (optional) The value to return if the
     *                          variable is not there.
     *
     * @return string     The cleaned form variable, or $default.
     */
    public function getFormData($var, $default = null)
    {
        return null !== ($val = self::getPost($var))
            ? $val : self::getGet($var, $default);
    }

    /**
     * Get a form variable from GET data, stripped of magic quotes if
     * necessary. This function will NOT return a POST variable.
     *
     *
     * @param string $var       The name of the form variable to look for.
     * @param null   $default   (optional) The value to return if the
     *                          variable is not there.
     *
     * @return string     The cleaned form variable, or $default.
     */
    public function getGet($var, $default = null)
    {
        return (array_key_exists($var, $_GET))
            ? self::dispelMagicQuotes($_GET[$var]) : $default;
    }

    /**
     * Get a form variable from POST data, stripped of magic quotes if
     * necessary. This function will NOT return a GET variable.
     *
     *
     * @param string $var       The name of the form variable to look for.
     * @param null   $default   (optional) The value to return if the
     *                          variable is not there.
     *
     * @return string     The cleaned form variable, or $default.
     */
    public function getPost($var, $default = null)
    {
        return (array_key_exists($var, $_POST))
            ? self::dispelMagicQuotes($_POST[$var]) : $default;
    }
}
