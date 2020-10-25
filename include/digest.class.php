<?php

// ------------------------------------------------------------------------- //
//                           FreeContent for Xoops                           //
//                              Version:  3.1                                //
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

require_once './include/wenli.class.php';

//global $fc_digest_max_stories;

class Digest
{
    public $source = '';

    public $time;

    public $headlines = [];

    public $_offset = 0;

    public $_regex = '';

    public $_digest_max_entries = 10;

    public function __construct()
    {
    }

    public function setSource($url)
    {
        $this->source = $url;
    }

    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    public function setRegex($regex)
    {
        $thgis->_regex = $regex;
    }

    public function setMaxEntries($num)
    {
        $this->_digest_max_entries = $num;
    }

    public function retrieveAbstract($url = '', $offset = 0, $regex = '', $max_entries = 10)
    {
        if ('' == $url) {
            $url = $this->source;
        }

        if ('' == $regex) {
            $regex = $this->_regex;
        }

        if ('' != $url) {
            $wenli = new Wenli($url);

            $data = $wenli->request_content();

            if (!$data) {
                return 'Either CURL extension is not installed in your PHP system or the returning data is empty<br><br> FreeContent needs CURL library functions to be operating';
            }

            $wenli->set_offset($offset);

            $wenli->set_regex($regex);

            $wenli->set_maxitems((int)$max_entries);

            if ('big5' == mb_substr(mb_strtolower(_CHARSET), 0, 4)) {
                $wenli->set_chinese_native_encoding(_FC_CHINESE_NATIVE_ENCODING_BIG5);
            } elseif ('gb' == mb_substr(mb_strtolower(_CHARSET), 0, 2)) {
                $wenli->set_chinese_native_encoding(_FC_CHINESE_NATIVE_ENCODING_GB2312);
            }

            $results = $wenli->extractTextLinkPairs($data);

            $ret = [];

            $i = 0;

            if (0 == count($results)) {
                //				$ret = 'This url returns no text, the page is probably using frames';

                $ret = false;
            } else {
                foreach ($results['textlink'] as $text) {
                    //					$ret .= '<a target="_blank" href="'.$results['url'][$i++].'"> '.$text.' &nbsp;</a><br><br>';

                    $entry = [];

                    $entry['link'] = $results['url'][$i++];

                    $entry['title'] = $text;

                    $ret[] = $entry;
                }
            }
        } else {
            $ret = false;
        }

        return $ret;
    }
}
