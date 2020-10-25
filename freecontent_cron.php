<?php

/*
  Author: James Valero <xoops@jamesvalero.com>
  Date: 5/23/04
  Purpose: Automatically updates every web digest for Freecontent 3.1
           Mainly so you don't need to manually click though every web
           digest link to update your ticker.
  License: Just keep this block of text with my name in the source
*/
// remove if you dont want someone to see the source
if (isset($_GET['highlight'])) {
    highlight_file(__FILE__);

    die();
}
// set to 1 if you are using a crontab / scheduled task and NOT using <img> trick
$is_cron = 1;
// make sure this file has write permission and exists
$filename = 'last_checked.txt';
// only update every x seconds (ignore if using crontab)
$update = 60 * 30;
// site xoops site setting does not use www.
//  (used to strip out www. prefix. If your site is set to www. set it to an empty string ''
$www = 'www.';
// sets the domain name (only change if using a cron / scheduled task)
$domain = ($_SERVER['SERVER_NAME']) ?: 'calpolymissa.com';
// gets the last time() the ticker was updated (ignored for cron)
$last_checked = (int)file_get_contents($filename) + $update;

/* DO NOT EDIT PAST THIS POINT */

// check if the ticker needs to be updated
if ($is_cron || time() > $last_checked || empty($last_checked)) {
    // your server with www. stripped out

    $domain = str_replace('www.', '', $domain);

    // the output html for freecontent digest

    $html = file_get_contents('http://' . $domain . '/modules/freecontent/');

    // grab every digest link

    preg_match_all('/<a href="(http:\/\/' . str_replace('.', '\.', $domain) . '\/modules\/freecontent\/index\.php\?url=http.*)">/', $html, $array);

    // randomizes which digest gets loaded incase the user doesn't stay that long on a particular page

    shuffle($array[1]);

    // load each digest to update the ticker

    foreach ($array[1] as $arr) {
        file_get_contents($arr);
    }

    // get ready to write the update time

    $fp = fopen($filename, 'wb');

    // can get exclusive lock otherwise someone else is updating the ticker

    if (flock($fp, LOCK_EX)) {
        fwrite($fp, time());
    }

    fclose($fp);
}
