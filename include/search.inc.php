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

function freecontent_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $ret = [];

    $sql = 'SELECT id, title, `comment` FROM ' . $xoopsDB->prefix('freecontent') . ' WHERE type=0 ';

    if (count($queryarray) > 0 && is_array($queryarray)) {
        $count = count($queryarray);

        $sql .= "AND ((title LIKE '%$queryarray[0]%' OR `comment` LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= $andor;

            $sql .= "(title LIKE '%$queryarray[$i]%' OR `comment` LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY id DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;

    while ($row = $xoopsDB->fetchArray($result)) {
        $ret[$i]['link'] = 'index.php?id=' . $row['id'] . '';

        $ret[$i]['title'] = $row['title'];

        $ret[$i]['time'] = '';

        $ret[$i]['uid'] = 1;

        $i++;
    }

    return $ret;
}
