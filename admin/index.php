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

require_once 'admin_header.php';
require_once '../include/freecontent.class.php';
include '../config.php';
/*********************************************************/
/*                           FreeContent - Admin                                  */
/*********************************************************/

xoops_cp_header();
$myts = MyTextSanitizer::getInstance();
echo '<br>';

$op = FreeContent::getFormData('op');
$id = FreeContent::getFormData('id');

$add = FreeContent::getFormData('add');
$form_title = FreeContent::getFormData('form_title');
$form_comment = FreeContent::getFormData('form_comment');
$form_address = FreeContent::getFormData('form_address');
$form_hide = FreeContent::getFormData('form_hide');
$form_hits = FreeContent::getFormData('form_hits');
$form_id = FreeContent::getFormData('form_id');
$form_digest = FreeContent::getFormData('form_digest');
$form_offset = FreeContent::getFormData('form_offset');
$form_regex = FreeContent::getFormData('form_regex');

switch ($op) {
        case 'edit':
                fc_admin_edit($id);
                break;
        case 'editdb':
        $form_title = $myts->addSlashes($form_title);
        $form_comment = $myts->addSlashes($form_comment);
        $form_address = $myts->addSlashes($form_address);
        $form_regex = $myts->addSlashes($form_regex);
                if ($form_hide) {
                    $form_hide = 1;
                } else {
                    $form_hide = 0;
                }

                $q = 'UPDATE ' . $xoopsDB->prefix() . "_freecontent SET title='" . $form_title . "', adress='" . $form_address . "', comment='" . $form_comment . "', hide='" . $form_hide . "', hits='" . $form_hits . "', type='" . $form_digest . "', design='" . $form_offset . "', special='" . $form_regex . "' WHERE id='" . $form_id . "'";

                if ($xoopsDB->query($q)) {
                    fc_admin_message(_FC_EDIT_DONE, 0, '');
                } else {
                    fc_admin_message(_FC_EDIT_DBERROR, 1, '');
                }

                fc_admin_list();
                fc_admin_add();
                fc_footer();
                break;
        case 'del':

                $result = $xoopsDB->queryF('SELECT adress FROM ' . $xoopsDB->prefix() . "_freecontent WHERE id='" . $id . "'", 1);
                if ($result) {
                    $fc_item = $xoopsDB->fetchArray($result);

                    $sql = 'DELETE FROM ' . $xoopsDB->prefix() . "_freecontent_newsticker WHERE source_url='" . $fc_item['adress'] . "'";

                    $xoopsDB->query($sql);
                }
                if ($xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix() . '_freecontent WHERE id=' . $id)) {
                    fc_admin_message(_FC_DEL_OK, 0, '');

                    // delete also comments for that item

                    xoops_comment_delete($xoopsModule->getVar('mid'), $id);
                } else {
                    fc_admin_message(_FC_EDIT_DBERROR, 1, '');
                }
                fc_admin_list();
                fc_admin_add();
                fc_footer();
                break;
        case 'delconfirm':
                OpenTable();
                $result = $xoopsDB->queryF('SELECT id, title, comment FROM ' . $xoopsDB->prefix() . "_freecontent WHERE id='" . $id . "'", 1);
                $fc_item = $xoopsDB->fetchArray($result);

                echo '<center><h4>' . _FC_DEL_REALLY . '</h4><br>' . $fc_item['id'] . ' <b>|</b> ' . htmlspecialchars($fc_item['title'], ENT_QUOTES | ENT_HTML5)
                     . ' <b>|</b> ' . htmlspecialchars($fc_item['comment'], ENT_QUOTES | ENT_HTML5)
                     . "<br><form action='index.php' method='post'><input type='hidden' name='id' value='" . $id . "'><input type='hidden' name='op' value='del'><input type='submit' value='" . _FC_DELETE . "'>&nbsp;<input type='button' value='" . _CANCEL . "' onclick='javascript:history.go(-1);'></form></center>";
                CloseTable();
                fc_footer();
                break;
        case 'add':

                $form_title = $myts->addSlashes($form_title);
                $form_comment = $myts->addSlashes($form_comment);
                $form_address = $myts->addSlashes($form_address);
                $form_offset = (int)$myts->addSlashes($form_offset);
                $form_regex = $myts->addSlashes($form_regex);
                if ($form_hide) {
                    $form_hide = 1;
                } else {
                    $form_hide = 0;
                }
                if ($form_digest) {
                    $form_digest = 1;
                } else {
                    $form_digest = 0;
                }
                $q = 'INSERT INTO ' . $xoopsDB->prefix() . "_freecontent (title, type, adress, comment, hide, design, special) VALUES ('" . $form_title . "', '" . $form_digest . "', '" . $form_address . "', '" . $form_comment . "', '" . $form_hide . "', '" . $form_offset . "', '" . $form_regex . "')";
                if ($xoopsDB->query($q)) {
                    fc_admin_message(_FC_EDIT_DONE, 0, '');
                } else {
                    fc_admin_message(_FC_EDIT_DBERROR, 1, '');
                }

                fc_admin_list();
                fc_admin_add();
                fc_footer();
                break;
        default:
                fc_admin_list();
                fc_admin_add();
                fc_footer();
                break;
}

xoops_cp_footer();

//*****************************************************************************************
//*** Functions-declaration ***************************************************************
//*****************************************************************************************

function fc_admin_list()
{
    global $xoopsDB, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    OpenTable();

    echo '<table border=0 cellpadding=2 cellspacing=2 width="95%"><tr><td colspan=8><div align="center">
	<b>' . _FC_LIST_HEADER . '</b></div></td></tr>
	<tr><td>' . _FC_ID . '</td><td>' . _FC_TITLE . '</td><td>' . _FC_ADRESS . '</td><td>' . 'Type' . '</td><td>' . _FC_HIDE . '</td><td>' . _FC_HITS . '</td><td>' . _FC_EDIT . '</td><td>' . _FC_DELETE . '</td></tr>';

    // get all rows from db

    $result = $xoopsDB->query('SELECT id, title, adress, type, hide, hits FROM ' . $xoopsDB->prefix() . '_freecontent');

    while ($fc_item = $xoopsDB->fetchArray($result)) {
        $linktype = ('0' == $fc_item['type']) ? 'normal' : 'digest';

        echo '<tr><td>' . $fc_item['id'] . '</td>
		<td>' . htmlspecialchars($fc_item['title'], ENT_QUOTES | ENT_HTML5) . '</td>
		<td>' . htmlspecialchars($fc_item['adress'], ENT_QUOTES | ENT_HTML5) . '</td>
		<td>' . $linktype . '</td>
		<td>' . $fc_item['hide'] . '</td>
		<td>' . $fc_item['hits'] . '</td>
		<td><a href="./index.php?op=edit&id=' . $fc_item['id'] . '">' . _FC_EDIT . '</a></td>
		<td><a href="./index.php?op=delconfirm&id=' . $fc_item['id'] . '">' . _FC_DELETE . '</a></td></tr>';
    }

    echo '</table>';

    CloseTable();
}

function fc_admin_add()
{
    global $xoopsConfig;

    OpenTable();

    echo '<form name="Add Content" action="./index.php" method="post"><div align="center">
                         <h4>' . _FC_ADD_HEADER . '</h4>
                </div><table border="0" cellpadding="2" cellspacing="2" width="95%">
                <tr>
                        <td align="right">' . _FC_TITLE . ':</td>
                                                <td><input type="text" name="form_title" size="50" tabindex="1"> </td>
                </tr>
                <tr>
                                                <td align="right">' . _FC_PATH_URL . ':</td>
                                                <td> Implicit path is relative to the default content root, full qualified URL for webdigest <input type="text" name="form_address" size="100" maxlength="255" value="tutorial.html" tabindex="2"></td>
                </tr>
                <tr>
                                                <td align="right">' . 'Description' . ':</td>
                                                <td><input type="text" name="form_comment" size="100" tabindex="3"></td>
                </tr>
                <tr>
                                                <td align="right">' . _FC_HIDE . ':</td>
                                                <td><input type="checkbox" value="checkboxValue" name="form_hide" tabindex="4"> ' . _FC_ADD_HIDELONG . '</td>
                </tr>
                <tr>
                                                <td align="right">' . 'Digest?' . ':</td>
                                                <td><input type="checkbox" value="digestValue" name="form_digest" tabindex="7"> ' . '(Check if the page should be showed in Digest Mode)' . '</td>
                </tr>
                <tr>
                                                <td align="right">' . 'Offset?' . ':</td>
                                                <td><input type="text" value="0" name="form_offset" size="4" tabindex="8"> ' . '(Ignore how many first links ? )' . '</td>
                </tr>
                <tr>
                        <td align="right">' . 'Filter (Regex)' . ':</td>
                                                <td><input type="text" name="form_regex" size="50" tabindex="9"> ' . "(Don't know what is regex ? leave it blank)" . '</td>
                </tr>
                        <tr height="10">
                                                <td align="right" height="10"></td>
                                                <td height="10"><input type="hidden" value="add" name="op"></td>
                </tr>
                <tr>
                                                <td align="right"></td>
                                                <td><input type="submit" name="add" tabindex="5" value="' . _FC_ADD_SUBMIT_ADD . '"> <input type="reset" tabindex="6" value="' . _FC_ADD_SUBMIT_RESET . '"></td>
                </tr></table></form>';

    CloseTable();
}

function fc_admin_edit($id)
{
    global $xoopsConfig, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT title, adress, type, comment, hide, hits, design, special FROM ' . $xoopsDB->prefix() . "_freecontent WHERE id='" . $id . "'");

    $fc_item = $xoopsDB->fetchArray($result);

    if (0 == $fc_item['hide']) {
        $hide_checked = '';
    } else {
        $hide_checked = 'checked';
    }

    if (0 == $fc_item['type']) {
        $digest_checked = '';
    } else {
        $digest_checked = 'checked';
    }

    OpenTable();

    echo '<form name="Edit Content" action="./index.php" method="post"><div align="center"><h4>' . _FC_EDIT_HEADER . '</h4></div><table border="0" cellpadding="2" cellspacing="2" width="95%">
                <tr>
                        <td align="right">' . _FC_ID . ':</td>
                                                <td><input type="text" value="' . $id . '" name="form_id" size="5" readonly> </td>
                </tr>
                <tr>
                        <td align="right">' . _FC_TITLE . ':</td>
                                                <td><input type="text" value="' . htmlspecialchars($fc_item['title'], ENT_QUOTES | ENT_HTML5) . '" name="form_title" size="50" tabindex="1"> </td>
                </tr>
                <tr>
                                                <td align="right">' . _FC_ADRESS . ':</td>
                                                <td> Implicit address is relative to the default content root <input type="text" name="form_address" size="100" maxlength="255"  value="' . htmlspecialchars($fc_item['adress'], ENT_QUOTES | ENT_HTML5) . '" tabindex="2"></td>
                </tr>
                <tr>
                                                <td align="right">' . 'Description' . ':</td>
                                                <td><input type="text" value="' . htmlspecialchars($fc_item['comment'], ENT_QUOTES | ENT_HTML5) . '" name="form_comment" size="100" tabindex="3"></td>
                </tr>
                <tr>
                                                <td align="right">' . _FC_HIDE . ':</td>
                                                <td><input type="checkbox" value="1" name="form_hide" tabindex="4" ' . $hide_checked . '> ' . _FC_ADD_HIDELONG . '</td>
                </tr>
                <tr>
                                                <td align="right">' . 'Digest?' . ':</td>
                                                <td><input type="checkbox" value="1" name="form_digest" tabindex="7" ' . $digest_checked . '> ' . '(Check if the page should be showed in Digest Mode)' . '</td>
                </tr>
                <tr>
                                                <td align="right">' . 'Offset?' . ':</td>
                                                <td><input type="text" value="' . htmlspecialchars($fc_item['design'], ENT_QUOTES | ENT_HTML5) . '" name="form_offset" size="4" tabindex="8"> (Ignore how many first links ?) </td>
                </tr>
                <tr>
                        <td align="right">' . 'Filter (Regex)' . ':</td>
                                                <td><input type="text" value="' . htmlspecialchars($fc_item['special'], ENT_QUOTES | ENT_HTML5) . "\" name=\"form_regex\" size=\"50\" tabindex=\"9\"> (Don't know what is regex ? leave it blank) </td>
                </tr>
                <tr>
                                                <td align=\"right\">" . _FC_HITS . ':</td>
                                                <td><input type="text" value="' . $fc_item['hits'] . '" name="form_hits" size="11" tabindex="5"></td>
                </tr>
                        <tr height="10">
                                                <td align="right" height="10"></td>
                                                <td height="10"><input type="hidden" value="editdb" name="op"></td>
                </tr>
                <tr>
                                                <td align="right"></td>
                                                <td><input type="submit" name="add" tabindex="6" value="' . _FC_SUBMIT_UPD . '"> <input type="reset" tabindex="7" value="' . _FC_ADD_SUBMIT_RESET . '"></td>
                </tr></table></form>';

    CloseTable();
}

function fc_admin_message($message_text, $error_color, $additional_text)
{
    OpenTable();

    if (0 == $error_color) {
        //Good News

        echo '<center><br><h4>' . $message_text . '</h4><br>' . $additional_text . '</center>';
    } else {
        //Bad News

        echo '<center><br><font color="red"><h4>' . $message_text . '</h4><br></font>' . $additional_text . '</center>';
    }

    CloseTable();
}

function fc_footer()
{
    global $fc_version;

    echo "<small><br>FreeContent $fc_version by <a href=\"http://www.guanxiCRM.com\">Wang Jue (wjue)</a><br>For Update: <a target\"_blank\" href=\"http://www.guanxiCRM.com\">http://www.guanxiCRM.com</a> or <a target\"_blank\" href=\"http://www.wjue.org\">http://www.wjue.org</a> (Chinese)</small>";
}
