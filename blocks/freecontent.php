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

function b_freecontent_show($options)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    // Query Database for link generation

    $result = $xoopsDB->query('SELECT id, title, hits FROM ' . $xoopsDB->prefix() . '_freecontent WHERE type=0 AND hide=0 ORDER BY title ASC');

    // generate links

    while ($fc_item = $xoopsDB->fetchArray($result)) {
        $fc_title = htmlspecialchars($fc_item['title'], ENT_QUOTES | ENT_HTML5);

        // shorten linktitles to fit into the block

        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($fc_title) >= 40) {
                $fc_title = mb_substr($fc_title, 0, 39) . '...';
            }
        }

        $freeContent['id'] = $fc_item['id'];

        $freeContent['title'] = $fc_title;

        $freeContent['hits'] = $fc_item['hits'];

        $block['contents'][] = $freeContent;
    }

    return $block;
}

function b_freecontent_magicnews_show($options)
{
    $block = [];

    global $xoopsDB;

    // Query Database for headlines

    $sql = 'SELECT source_url, headlines FROM ' . $xoopsDB->prefix() . '_freecontent_newsticker ORDER BY updatetime DESC';

    $result = $xoopsDB->queryF($sql);

    if ($result) {
        // We take only 1 row

        if ($row = $xoopsDB->fetchRow($result)) {
            $src = $row[0];

            $headlines = $row[1];
        }

        $headlines = unserialize(base64_decode($headlines, true));
    }

    $block['contents'] = '';

    foreach ($headlines as $item) {
        $block['contents'] .= '<a target="_blank" href="' . $item['link'] . '"> ' . $item['title'] . ' &nbsp;</a><br><br>';
    }

//    $cacheDir = XOOPS_ROOT_PATH.'/cache/';
//    $cacheFile = 'magicnews.cache.serialized';
//    if ($fp = @fopen($cacheDir.$cacheFile, "r") ) {
//        $data = fread( $fp, filesize($cacheDir.$cacheFile) );
//        $headlines = unserialize( base64_decode($data) );
//        fclose($fp);
//        $block['contents'] = '';
//        foreach ($headlines as $item) {
//            $block['contents'] .= '<a target="_blank" href="'.$item['link'].'"> '.$item['title'].' &nbsp;</a><br><br>';
//        }
//    } else {
//        $block['contents'] = '<div align="center">Webdigest all WWW in your website<br><br><small>powered by </small><a href="http://www.guanxiCRM.com"><small>FreeContent</small></a></div>';
//    }

    return $block;
}

function b_newsticker_show($options)
{
    //$myts = MyTextSanitizer::getInstance();

    $block = [];

    $ONE_DAY = 86400;

    global $xoopsDB;

    // Query Database for headlines

    $sql = 'SELECT source_url, headlines FROM ' . $xoopsDB->prefix() . '_freecontent_newsticker WHERE ' . time() . '<updatetime+' . $ONE_DAY . ' ORDER BY updatetime DESC';

    $result = $xoopsDB->queryF($sql);

    $ticker = [];

    if ($result) {
        while (false !== ($row = $xoopsDB->fetchRow($result))) {
            $pool[] = $row[1];
        }

        // We take a random row

        if (!empty($pool)) {
            $rand_key = array_rand($pool);

            $ticker = unserialize(base64_decode($pool[$rand_key], true));
        }
    }

    if (empty($ticker)) {
        // try to retrieve the most recent entry if any

        $sql = 'SELECT source_url, headlines FROM ' . $xoopsDB->prefix() . '_freecontent_newsticker ORDER BY updatetime DESC';

        $result = $xoopsDB->queryF($sql);

        if ($result) {
            if ($row = $xoopsDB->fetchRow($result)) {
                $ticker = unserialize(base64_decode($row[1], true));
            }
        }
    }

    /*
        $adv = array();
        $adv['link'] = "http://www.china-offshore.com";
        $adv['title'] = '<font color="RED"><b>Software&nbsp;Outsourcing&nbsp;to China&nbsp;: The&nbsp;Successful Way</b></font>';
        $ticker[  intval(sizeof($ticker))  ] = $adv;
      */

    $myTimeout = 4000;

    $Titlenum = count($ticker);

    ob_start();

    $NextStoryFunction = 'function NextStory(){' . "\n";

    for ($i = 0; $i < $Titlenum; $i++) {
        $showTitle = addslashes(mb_substr(($ticker[$i]['title']), 0, 160));

        $NextStoryFunction .= 'if (nowTitle==' . $i . ') {myTitle = "' . $showTitle . '"; myUrl = "' . addslashes($ticker[$i]['link']) . '"};' . "\n";
    }

    $NextStoryFunction .= 'nowTitle++;' . "\n";

    $NextStoryFunction .= 'if (nowTitle == allTitle) nowTitle = 0; }';

    echo <<<EOB
<script language="javascript">
var myTitle, myUrl, myTimeout, flag, allTitle, nowTitle, myPos;
myTimeout = $myTimeout; flag = "end"; myPos = 0;
allTitle = $Titlenum; nowTitle = 0; myTitle = " "; myUrl = "/";
function runTicker(){
    NextStory();
	drawStory();
}
$NextStoryFunction
function drawStory(){
	window.myTicker.innerHTML = '<a target=_blank href=\"' + myUrl + '\">' + myTitle + '</a>';
    setTimeout("runTicker()", myTimeout);
}
</script>
<!-- <div id="myTicker" class="topnews"></div> -->
<div id="myTicker"
    style="
	    width: 100%;
	    top: 0px;
	    left: 0px;
	    height: 18px;
	    overflow-x: hidden;
	    overflow-y: hidden;
	    ">
</div>
<script language="javascript">
setTimeout("runTicker()", myTimeout);
</script>
EOB;

    $block['contents'] = ob_get_contents();

    ob_end_clean();

    return $block;
}
