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

define('_FC_CHINESE_NATIVE_ENCODING_NULL', 0);
define('_FC_CHINESE_NATIVE_ENCODING_GB2312', 1);
define('_FC_CHINESE_NATIVE_ENCODING_BIG5', 2);

class Wenli
{
    public $charset = 'iso-8859-1';			// default charset of fetched web page
    public $minLength = 30;                    // min Length of text part of (link,text) pairs for non DByte Languages
    public $minLength_zh = 20;                 // min Length of text part of (link,text) pairs for Chinese Language
    public $minWords = 4;                     // min number of word of text part of (link,text) pairs for European Language
    public $maxitems = 10;                    // default maximum items returned
    public $offset = 0;                     // Sometimes the first links of webpages are commercial so drop it !

    public $sort = false;                     // whether or not to sort the results
    public $regex = '';                        // regular expression filter

    public $datasrc = '';                      // data source
    public $results = [];                 // the result array of (URL, TextLink) pairs

    public $src_url = '';                      // the url of the data source, used for expending links to full qualified urls

    public $chinese_native_encoding = _FC_CHINESE_NATIVE_ENCODING_NULL;      // possible values: 'big5', or 'gb2312'

    public $_parsed_url = '';

    public $_specific_filter_type = 'none';    // Specific filter used for Google, Yahoo...
    public $_pool = [];                   // internal unique title pool

    /*
    *  private data
    */

    public $_FilterRegex = "click (here)* to (buy)*|all rights reserved|copyright|tous droits reserv|.+@.+\.|buy now|visit our sponsor|(your )*home page|document\.write\(";

    public function __construct($src)
    {
        $this->src_url = $src;

        if (preg_match('/^http:\/\/news\.google\.com/i', $src, $match)) {
            $this->_specific_filter_type = 'google_news';
        } elseif (preg_match('/^http:\/\/news\.yahoo\.com/i', $src, $match)) {
            $this->_specific_filter_type = 'yahoo_news';
        }

        $this->_parsed_url = parse_url($src);
    }

    public function set_chinese_native_encoding($type)
    {
        if ((_FC_CHINESE_NATIVE_ENCODING_GB2312 == $type) || (_FC_CHINESE_NATIVE_ENCODING_BIG5 == $type)) {
            $this->chinese_native_encoding = $type;
        }
    }

    public function set_sort($sort)
    {
        if ($sort) {
            $this->sort = true;
        }
    }

    public function set_offset($offset)
    {
        if ($offset > 0) {
            $this->offset = $offset;
        }
    }

    public function set_regex($regex)
    {
        $this->regex = $regex;
    }

    public function set_maxitems($max)
    {
        if ($max > 0) {
            $this->maxitems = $max;
        }
    }

    public function set_minLength($len)
    {
        if (!empty($len)) {
            $this->minLength = $len;
        }
    }

    public function set_minWords($len)
    {
        if (!empty($len)) {
            $this->minWords = $len;
        }
    }

    /*======================================================================*\
    Function:	setcharset
    Purpose:	set the charset of the data
    \*======================================================================*/

    public function setcharset($data)
    {
        // find the charset in

        // <meta http-equiv="Content-Type" content="text/html; charset=big5">

        if (0 !== preg_match('|<meta.+?charset\s*=(.+?)[>\s\'\"]|i', $data, $match)) {
            //	    	$charset = preg_replace('/[=\"\/\s\']/',"",$match[1]);

            $this->charset = mb_strtolower($match[1]);
        }
    }

    /*======================================================================*\
    Function:	extractTextLinkPairs
    Purpose:	strip the hyperlinks from the data src
    Return:     array of (link, textlink)
    \*======================================================================*/

    public function extractTextLinkPairs(&$data)
    {
        $results = false;

        if ('' !== $data) {
            $results = [];

            $data = str_replace("\r\n", ' ', $data);

            $data = str_replace("\n", ' ', $data);

            // reduce excessif white spaces

            $data = preg_replace("/\s+/", ' ', $data);

            // strip out javascript texts

            $data = preg_replace('/<script.+?script>/si', '', $data);

            $dada = preg_replace('/<!--.+?-->/', '', $data);

            $this->setcharset($data);

            //       $match_pattern = "/href\s*=\s*(\"|')?\s*(?![\"']?mailto:)/i";     //email links are not retrieved

            //       preg_match_all($match_pattern, $data, $urlTextLinks);

            if ('' == $data) {
                return false;
            }

            preg_match_all("'<\s*a.+?href\s*=\s*		         	                               # find <a href=
						([\"\'])?		             			                               # find single or double quote  
                        (?(1) (.*?)\\1.*?>(.+?)</a>|(.*?)					               # if quote found, match up to next matching 
                                                         ([\s])?                             # quote, otherwise match up to next space
                                                         (?(5) .*?>(.+?)</a>|>(.+?)</a>))  
						'isx", $data, $urlTextLinks);

            // concatenate the non-empty matches from the conditional subpattern

            $i = 0;

            $match['textlink'] = [];

            $match['url'] = [];

            foreach ($urlTextLinks[0] as $val) {
                $tempTX = $this->TextFilter(trim(strip_tags($urlTextLinks[3][$i] . $urlTextLinks[6][$i] . $urlTextLinks[7][$i])));

                $temp = explode(' ', trim($urlTextLinks[2][$i] . $urlTextLinks[4][$i]));

                $tempLK = $this->LinkFilter($temp[0]);

                if (('' !== $tempTX) && ('' !== $tempLK)) {
                    $match['url'][] = $tempLK;

                    $match['textlink'][] = $tempTX;
                }

                $i++;
            }

            if ($this->sort) {
                $i = 0;

                foreach ($match['textlink'] as $Text) {
                    $sortarray[$i] = mb_strlen($match['textlink'][$i]);  //links with lengthier TEXT having priority

                    $i++;
                }

                array_multisort($sortarray, SORT_DESC, SORT_NUMERIC, $match['url'], $match['textlink']);
            }

            // return filtered (url,TextLink) pairs as $results

            $i = 0;

            $j = 0;

            $charset = mb_strtolower($this->charset);

            if (in_array($charset, ['gb2312', 'gb', 'big5', 'gbk'], true)) {
                $charset = ('big5' == $charset) ? 'big5' : 'gb2312';

                if ('big5' == $charset && _FC_CHINESE_NATIVE_ENCODING_GB2312 == $this->chinese_native_encoding) {
                    $conversion_type = 'big5Togb';
                } elseif ('gb2312' == $charset && _FC_CHINESE_NATIVE_ENCODING_BIG5 == $this->chinese_native_encoding) {
                    $conversion_type = 'gbTobig5';
                } else {
                    $conversion_type = '';
                }

                if ('' != $conversion_type) {
                    global $Ziling;

                    if (!is_object($Ziling)) {
                        // gb2312 <-> big5 conversion

                        require XOOPS_ROOT_PATH . '/modules/freecontent/include/ziling_includes/ziling.class.php';

                        $Ziling = new Ziling(XOOPS_ROOT_PATH . '/ziling');
                    }
                }

                $minLength = $this->minLength_zh;

                foreach ($match['textlink'] as $Text) {
                    if ((mb_strlen($Text) > $minLength) && ($j < ($this->maxitems + $this->offset))) {
                        if ($j >= $this->offset) {
                            if ('gbTobig5' == $conversion_type) {
                                $Text = $Ziling->Gb_Big5($Text);
                            } elseif ('big5Togb' == $conversion_type) {
                                $Text = $Ziling->Big5_Gb($Text);
                            }

                            $results['textlink'][] = $Text;

                            $results['url'][] = $this->expandlink($match['url'][$i]);
                        }

                        $j++;
                    }

                    $i++;
                }
            } else {
                $minLength = $this->minLength;

                $minWords = $this->minWords;

                foreach ($match['textlink'] as $Text) {
                    $words = preg_preg_split("|[\s,;]+|", $Text);

                    $numOfWords = count($words);

                    if ((mb_strlen($Text) > $minLength) && ($numOfWords > $minWords) && ($j < $this->maxitems + $this->offset)) {
                        // if $Text differs very little from $precedent then don't show !

                        if (!$this->_alreadySeen($Text, $minLength)) {
                            if ($j >= $this->offset) {
                                $results['textlink'][] = $Text;

                                $tmp = rawurldecode($this->expandlink($match['url'][$i]));

                                if (('google_news' == $this->_specific_filter_type) &&
                                (preg_match('/http:\/\/news\.google\.com\/.+?&q=(.+)/i', $tmp, $found))) {
                                    $results['url'][] = $found[1];
                                } elseif (('yahoo_news' == $this->_specific_filter_type) &&
                                (preg_match('/.+?\/\*(.+)/i', $tmp, $found))) {
                                    $results['url'][] = $found[1];
                                } else {
                                    $results['url'][] = $tmp;
                                }
                            }

                            $j++;

                            $precedent = $Text;
                        }
                    }

                    $i++;
                }
            }
        }

        return $results;
    }

    public function _alreadySeen($new, $length, $multibyte = false)
    {
        if ($multibyte) {
            $new = mb_substr($new, 0, $length);
        } else {
            $new = mb_strtolower(mb_substr($new, 0, $length));
        }

        if (!in_array($new, $this->_pool, true)) {
            $this->_pool[] = $new;

            return false;
        }

        return true;
    }

    public function TextFilter($mystring)
    {
        //site specifique filter

        //        if ($this->_specific_filter_type == 'google_news') {

        //            if ( preg_match('/and.+?[0-9]+.+?related.*/i', $mystring, $found) ) {

        //                return "";

        //            }

        //        }

        //        elseif ($this->_specific_filter_type == 'yahoo_news') {

        //            if ( preg_match('/and.+?[0-9]+.+?related.*/i', $mystring, $found) ) {

        //                return "";

        //        }

        if ('' !== $this->regex) {
            $this->_FilterRegex .= '|' . $this->regex;
        }

        if (preg_match('/' . $this->_FilterRegex . '/i', $mystring, $match)) {
            return '';
        }

        return $mystring;
        //		    return htmlentities($mystring);
            //		    return $this->_unhtmlentities($mystring);
    }

    public function LinkFilter($string)
    {
        //site specifique filter

        if ('google_news' == $this->_specific_filter_type) {
            if (preg_match('/newsalerts\?q=|about\.html|&q=cluster/i', $string, $found)) {
                return '';
            }
        }

        return $string;
    }

    public function _unhtmlentities($string)
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);

        $trans_tbl = array_flip($trans_tbl);

        $ret = strtr($string, $trans_tbl);

        return preg_replace(
            '/\&\#([0-9]+)\;/me',
            "chr('\\1')",
            $ret
        );
    }

    public function expandlink($link)
    {
        $url = $this->_parsed_url;

        if (preg_match('/^http:/i', $link, $match)) {
            return $link;
        } elseif (preg_match('/^\//i', $link, $match)) {
            return $this->_parsed_url['scheme'] . '://' . $this->_parsed_url['host'] . $link;
        }

        return ($this->basedir_from_url($url) . $link);
    }

    public function basedir_from_url($parsed)
    {
        if (!is_array($parsed)) {
            return false;
        }

        $basedir = $parsed['scheme'] ? $parsed['scheme'] . ':' . (('mailto' == mb_strtolower($parsed['scheme'])) ? '' : '//') : '';

        $basedir .= @$parsed['user'] ? $parsed['user'] . ($parsed['pass'] ? ':' . $parsed['pass'] : '') . '@' : '';

        $basedir .= @$parsed['host'] ? $parsed['host'] : '';

        $basedir .= @$parsed['port'] ? ':' . $parsed['port'] : '';

        if (!empty($parsed['path'])) {
            if ((preg_match('|(.*)/$|', $parsed['path'], $match))) {
                $basedir .= $parsed['path'];
            } elseif (preg_match('/(.*\/).*?\.(php$|php3$|htm$|html$|shtml$|asp$|aspx$|jsp$)/i', $parsed['path'], $match)) {
                $basedir .= $match[1];
            } else {
                $basedir .= $parsed['path'] . '/';
            }
        } else {
            $basedir .= '/';
        }

        return $basedir;
    }

    public function expandlinks($links)
    {
        if (is_array($links)) {
            $i = 0;

            foreach ($links as $link) {
                $links[$i++] = $this->expandlink($link);
            }
        }

        return $links;
    }

    public function request_content()
    {
        // fetch a remote www page

        if (!function_exists('curl_init')) {
            return false;
        }

        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $this->src_url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 16s
        $data = curl_exec($ch); // run the whole process
        //update the src url in case of redirection
        $this->src_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

        curl_close($ch);

        return $data;
    }
}
