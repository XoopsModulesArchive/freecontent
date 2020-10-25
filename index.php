<?php

// ------------------------------------------------------------------------- //
//                           FreeContent for Xoops                           //
//                              Version:  3.x                                //
// ------------------------------------------------------------------------- //
// Author: Wang Jue (alias wjue)                                             //
// Purpose: Digest Headlines (WebDigest) from any webpage.                   //
//          wrap html or php-content into nice Xoops design.                 //
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
//The compatibility Xoops wrapping method uses v1.0 of Stefan "SibSerag" Oese//

include 'header.php';
include 'config.php';
require_once './include/freecontent.class.php';

$id = FreeContent::getFormData('id');
$path = FreeContent::getFormData('path');
$op = FreeContent::getFormData('op');
$offset = FreeContent::getFormData('of');
$url = FreeContent::getFormData('url');
$regex = FreeContent::getFormData('re');

if (isset($id) || isset($path)) {
    $op = 'cl';
}

if ('digest' != mb_strtolower($fc_start_mode)) {
    if (!isset($id) && !isset($path)) {
        $id = $fc_start_id;

        $op = $op ?? 'cl';
    }
} else {
    $op = $op ?? 'di';
}
$headPart = '';
$bodyPart = '';

switch ($op) {
    case 'cl':
    $GLOBALS['xoopsOption']['template_main'] = 'fc_index.html';
    require XOOPS_ROOT_PATH . '/header.php';
    if (isset($path) && '' != $path) {
        $fc_path = freecontent_path(parse_url($path));

        $fc_data = file($fc_path);

        if ($fc_data) {
            $fc_data = implode('', $fc_data);
        }

        if ('yes' == $fc_xhtml_compliance) {
            pretty_include_html($fc_data, $headPart, $bodyPart);
        } else {
            $bodyPart = &$fc_data;
        }

        // run wrapper
        //query Database (returns an array)
    } else {
        $fc_item = fc_query_db_on_id($id);

        //$id exists?

        if (isset($fc_item['adress']) && '' != $fc_item['adress']) {
            // include content

            // for compatibility with legacy data

            $fc_item['adress'] = preg_replace('|^modules/freecontent/content/|i', '', $fc_item['adress']);

            $fc_include = '/' . $fc_content_root_url . '/' . $fc_item['adress'];

            $fc_include = XOOPS_ROOT_PATH . str_replace('//', '/', $fc_include);        // abondance de precautions !

            $headPart = '';

            $bodyPart = '';

            if (file_exists($fc_include)) {
                ob_start();

                include $fc_include;

                $fc_data = ob_get_contents();

                ob_end_clean();

                if ('yes' == $fc_xhtml_compliance) {
                    pretty_include_html($fc_data, $headPart, $bodyPart);
                } else {
                    $bodyPart = &$fc_data;
                }

                // increment hitcounter (hits)

                $res = $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix() . '_freecontent SET hits=hits+1 WHERE id="' . $id . '"');
            } else {
                $bodyPart .= _FC_FILENOTFOUND . $fc_include;
            }
        } else {
            $bodyPart .= _FC_IDNOTFOUND;
        }
    }
        break;
    default:
    // news headline feature from any external site

    $GLOBALS['xoopsOption']['template_main'] = 'fc_digest.html';
    require XOOPS_ROOT_PATH . '/header.php';
    $freecontent['src'] = fc_news_hl_nav();

    if (isset($url)) {
        $url = (preg_match('|^http://(.*)|i', $url, $match)) ? $url : 'http://' . $url;

        $decoded_url = rawurldecode($url);

        $regex = rawurldecode($regex);

        $pointpointpoint = (mb_strlen($decoded_url) > 40) ? '...' : '';

        $headlines = digest($decoded_url, $offset, $regex);

        $freecontent['digest'] = flat_content($headlines);

        //serialise this content for Magic News block

        if ($headlines && !isset($_POST['url'])) {
            store_for_magicnews($url, $headlines);
        }

        $freecontent['digest'] .= '<br><br>' . _FC_WEBDIGEST_PROMPT . ' : <a target="_blank" href="' . $decoded_url . '">' . mb_substr($decoded_url, 0, 40) . $pointpointpoint . '</a> <br><br>';
    } else {
        ob_start();

        include(XOOPS_ROOT_PATH . '/modules/freecontent/digest_start.html');

        $freecontent['digest'] = ob_get_contents();

        ob_end_clean();

        $freecontent['current'] = '';
    }

    $freecontent['title'] = _FC_WEBDIGEST;
    $freecontent['form'] = '<form name="userURL" method="post" action="' . XOOPS_URL . '/modules/freecontent/index.php?op=d">' . '<input type="text" name="url" size="60" maxlength="180" value=""></form>';

    $freecontent['prompt'] = _FC_ENTER_URL_TOWEBDIGEST . ' :';
    $xoopsTpl->assign('freecontent', $freecontent);
}
// Please do not remove this credit notice, read the license information
$bodyPart .= '<hr><div align="center"><small>powered by <a href="http://www.wjue.org">FreeContent ' . $fc_version . ' &copy; wjue.org</a> & <a href="http://www.guanxicrm.com">guanxiCRM.com</a></small></div>';

$xoopsTpl->assign('xoops_module_header', $headPart);
$xoopsTpl->assign('fcContent', $bodyPart);
//$xoopsTpl->assign('xoops_onload', $extra);

require XOOPS_ROOT_PATH . '/include/comment_view.php';

require XOOPS_ROOT_PATH . '/footer.php';

function fc_query_db_on_id($id)
{
    global $xoopsDB;

    //query Database (returns an array)

    $result = $xoopsDB->queryF('SELECT adress FROM ' . $xoopsDB->prefix() . '_freecontent WHERE id="' . $id . '"', 1);

    return $xoopsDB->fetchArray($result);
}

function pretty_include_html($htmlData, &$headPart, &$bodyPart)
{
    $htmlData = str_replace("\r\n", ' ', $htmlData);

    $htmlData = str_replace("\n", ' ', $htmlData);

    // reduce excessif white spaces

    $htmlData = preg_replace("/\s+/", ' ', $htmlData);

    split_head_body_parts($htmlData, $headPart, $bodyPart);
}

function split_head_body_parts($htmlData, &$headPart, &$bodyPart)
{
    global $fc_rewrite_url;

    $extra = '';     // for future use

    if (0 !== preg_match('|<\s*head\s*>(.*)<\s*/\s*head\s*>.*?<\s*body\s?.*?>(.*)<\s*/\s*body\s*>|i', $htmlData, $match)) {
        $headPart = $match[1];

        $bodyPart = $match[2];

        // for the moment we just extract css and javascript info from the html header

        preg_match_all('|<\s*link\s+.*?>|i', $headPart, $match);

        $headPart_tmp = implode(' ', $match[0]);

        preg_match_all('|<\s*script\s+.*?>\s*<\s*/\s*script\s*>|i', $headPart, $match);

        $headPart = $headPart_tmp . implode(' ', $match[0]);

        if ('yes' == $fc_rewrite_url) {
            $bodyPart = rewrite_url($bodyPart);
        }
    } else {
        $headPart = '';

        $bodyPart = '';
    }

    $headPart .= '<!-- Powered by FreeContent Webdigest, http://www.guanxiCRM.com -->';
}

function rewrite_url($content)
{
    // rewrite all links
    $match_pattern = "/href\s*=\s*(\"|')?\s*(?![\"']?mailto:)/i";     //We preserve email links
    $replacement = 'href=' . '\\1' . XOOPS_URL . '/modules/freecontent/index.php?path=';

    $content = preg_replace($match_pattern, $replacement, $content);

    return $content;
}

function freecontent_path($parsed)
{
    global $fc_content_root_url;

    if (!is_array($parsed)) {
        return false;
    }

    $ret_1 = @$parsed['scheme'] ? @$parsed['scheme'] . '://' : '';

    $ret_1 .= @$parsed['user'] ? @$parsed['user'] . (@$parsed['pass'] ? ':' . @$parsed['pass'] : '') . '@' : '';

    $ret_1 .= @$parsed['host'] ?: '';

    $ret_1 .= @$parsed['port'] ? ':' . @$parsed['port'] : '';

    $ret_2 = @$parsed['path'] ?: '';

    $ret_2 .= @$parsed['query'] ? '?' . @$parsed['query'] : '';

    $ret_2 .= @$parsed['fragment'] ? '#' . @$parsed['fragment'] : '';

    //if the url is not full qualified then we append with XOOPS_URL

    if (!@$parsed['scheme']) {
        $ret = '/' . $fc_content_root_url . '/' . $ret_2;

        $ret = XOOPS_URL . str_replace('//', '/', $ret);                  // abondance de precautions !
    } else {
        $ret = $ret_1 . $ret_2;

        //if the content page is outside your Xoops site (XOOPS_URL) we redirect to it

        $match_pattern = '|^' . XOOPS_URL . '|i';

        if (0 == preg_match($match_pattern, $ret)) {
            header('Location: ' . $ret);
        }

        //if the link is an internal Xoops style url, redirect

        $match_pattern = '|^' . XOOPS_URL . '/modules/' . '|i';

        if (preg_match($match_pattern, $ret) > 0) {
            header('Location: ' . $ret);
        }
    }

    return $ret;
}

// News headline navigation
function fc_news_hl_nav()
{
    global $xoopsDB;

    $ret = [];

    $sql = 'SELECT id, title, adress, design, special FROM ' . $xoopsDB->prefix('freecontent') . ' WHERE type=1 ORDER BY title';

    if ($result = $xoopsDB->query($sql)) {
        while (list($id, $title, $adress, $offset, $regex) = $xoopsDB->fetchRow($result)) {
            $ret[] = '<a href="' . XOOPS_URL . '/modules/freecontent/index.php' . '?' . 'url=' . rawurlencode($adress) . '&op=di&of=' . $offset . '&re=' . rawurlencode($regex) . '">' . $title . '&nbsp;</a>';
        }
//        $ret[] =  "<a href=\"http://www.guanxiCRM.com\">guanxiCRM.com</a>";
    }

    return $ret;
}

// Returns an array of (link, title) pairs, false if failed
function digest($url, $offset = 0, $regex = '')
{
    global $fc_digest_max_stories;

    require_once './include/wenli.class.php';

    $wenli = new Wenli($url);

    $data = $wenli->request_content();

    if (!$data) {
        return 'Either CURL extension is not installed in your PHP system or the returning data is empty<br><br> FreeContent needs CURL library functions to be operating';
    }

    $wenli->set_offset($offset);

    $wenli->set_regex($regex);

    $wenli->set_maxitems((int)$fc_digest_max_stories);

    if ('big5' == mb_substr(mb_strtolower(_CHARSET), 0, 4)) {
        $wenli->set_chinese_native_encoding(_FC_CHINESE_NATIVE_ENCODING_BIG5);
    } elseif ('gb' == mb_substr(mb_strtolower(_CHARSET), 0, 2)) {
        $wenli->set_chinese_native_encoding(_FC_CHINESE_NATIVE_ENCODING_GB2312);
    }

    $results = $wenli->extractTextLinkPairs($data);

    $ret = [];

    $i = 0;

    if (0 == count($results)) {
//        $ret .= 'This url returns no text, the page is probably using frames';

        $ret = false;
    } else {
        foreach ($results['textlink'] as $text) {
//            $ret .= '<a target="_blank" href="'.$results['url'][$i++].'"> '.$text.' &nbsp;</a><br><br>';

            $entry = [];

            $entry['link'] = $results['url'][$i++];

            $entry['title'] = $text;

            $ret[] = $entry;
        }
    }

    return $ret;
}

function flat_content($digest_array)
{
    if (is_array($digest_array)) {
        $ret = '';

        foreach ($digest_array as $item) {
            $ret .= '<a target="_blank" href="' . $item['link'] . '"> ' . $item['title'] . ' &nbsp;</a><br><br>';
        }
    } else {
        $ret = 'This url returns no text, the page is probably using frames';
    }

    return $ret;
}

/*
**  $mnews is an array of (link, headline title)
*/

function store_for_magicnews($url, $mnews)
{
    global $xoopsDB;

    $headlines = base64_encode(serialize($mnews));

    $nowtime = time();

    $sql = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix() . "_freecontent_newsticker WHERE source_url='" . $url . "'";

    $result = $xoopsDB->queryF($sql);

    $row = $xoopsDB->fetchRow($result);

    if ($row[0] > 0) {
        $sql = 'UPDATE ' . $xoopsDB->prefix() . '_freecontent_newsticker' . " SET headlines='" . $headlines . "', updatetime='" . $nowtime . "' WHERE source_url='" . $url . "'";

        $result = $xoopsDB->queryF($sql);
    } else {
        // fresh add

        $sql = 'INSERT INTO ' . $xoopsDB->prefix() . '_freecontent_newsticker' . " (source_url, headlines, updatetime) VALUES ('$url', '$headlines', '$nowtime') ";

        $result = $xoopsDB->queryF($sql);
    }
//    if (is_array($mnews)) {
//        $cacheDir = XOOPS_ROOT_PATH.'/cache/';
//        $cacheFile = 'magicnews.cache.serialized';
//
//        $fp = fopen($cacheDir.$cacheFile, "w");
//        fwrite( $fp, $headlines );
//        fclose($fp);
//    }
}
