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
//***************************************************************************//

// Freecontent version number
$fc_version = '3.2';
// Startup mode could be 'wrap' or 'digest'
#$fc_start_mode = 'wrap';
$fc_start_mode = 'digest';
// (eng) Link-ID of the ContentConnection which shows up when running FreeContent as Classic Startpage (wrap mode)
$fc_start_id = 1;
// if set to anything other then 'yes' the plain old wrapping method will be used
// change only if the default new wrapping method doesn't work
$fc_xhtml_compliance = 'yes';
// default content root url, relative to XOOPS_URL
// defaut is '/modules/freecontent/content/' ,
// the leading and terminating '/' are indispensable
$fc_content_root_url = '/modules/freecontent/content/';

// switch of the URL ReWritting function, if you want to disable this feauture turn it to anything other then 'yes'
$fc_rewrite_url = 'yes';

// maximum number of stories in Digest Mode
$fc_digest_max_stories = 15;
