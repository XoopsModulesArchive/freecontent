<?php

// ------------------------------------------------------------------------- //
//                           FreeContent for Xoops                           //
//                              Version:  2.9                                //
// ------------------------------------------------------------------------- //
// Author: Wang Jue (alias wjue)                                             //
// Purpose: Module to wrap html or php-content into nice Xoops design.       //
// email: wjue@wjue.org                                                      //
// URLs: http://www.wjue.org,  http://www.guanxiCRM.com                      //
// This program is partly based on the work of Stefan "SibSerag" Oese        //
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
//* Caution: FreeContent v2.x is NOT compatible with Xoops 1.3.x           *//
//*          Use FreeContent 1.2 for that purpose                           *//
//***************************************************************************//

//%%%%%%	Admin Module Name  FreeContent	%%%%%
define('_FC_ADMINTITLE', 'FreeContent - Admin');
define('_FC_LIST_HEADER', 'All active content connections');

define('_FC_ID', 'LinkID');
define('_FC_TITLE', 'Title');
define('_FC_ADRESS', 'Path of the Include file');
define('_FC_PATH_URL', 'Local File Path or URL');
define('_FC_COMMENT', 'Comment');
define('_FC_HIDE', 'Hidden?');
define('_FC_DELETE', 'Delete');
define('_FC_HITS', 'Hits');

define('_FC_ADD_HEADER', 'Add new content connection:');
define('_FC_ADD_HIDELONG', '(Do not show a link to this content in the FreeContent-Block)');
define('_FC_ADD_SUBMIT_ADD', 'Add Connection');
define('_FC_ADD_SUBMIT_RESET', 'Reset');

define('_FC_EDIT_HEADER', 'Edit a content connection:');
define('_FC_EDIT', 'Edit');
define('_FC_SUBMIT_UPD', 'Update');
define('_FC_EDIT_DBERROR', 'An database error occured!');
define('_FC_EDIT_DONE', 'Content connection updated!');

define('_FC_DEL_OK', 'Content connection deleted!');
define('_FC_DEL_REALLY', 'Delete this content connection?');

define('_FC_DB_CREATED', 'Table created!');
define('_FC_DB_MUST', 'When Running FreeContent for the first time you have to create the needed database-table!');
define('_FC_DB_LINK', '<a href="index.php?op=createdb">Click here to create the table...</a>');
