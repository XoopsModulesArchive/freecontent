<?php

include '../../mainfile.php';

global $xoopsDB,$xoopsConfig;
require XOOPS_ROOT_PATH . '/header.php';

function install_header()
{
    ?>
	<br><br><div style='text-align:center'><h4>Update</h4>
<?php
}

function install_footer()
{
    ?>
   </div>
<?php
}

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

foreach ($_GET as $k => $v) {
    ${$k} = $v;
}

if (!isset($action) || '' == $action) {
    $action = 'message';
}

if ('message' == $action) {
    install_header();

    echo "
  <table width='100%' border='0'>
  <tr>
    <td align='center'><b>Welcome to the Freecontent update script</b></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>";

    echo "
	<table width='50%' border='0'><tr><td colspan='2'>This script will upgrade your Freecontent 3.0 to 3.1 <br><br><b>Before upgrading, make sure that you have:<b></td></tr>

	<tr><td></td><td >Uploaded all the contents of the Freecontent 3.1 package to your server! </td></tr>
	<tr><td></td><td><span style='color:#ff0000;font-weight:bold;'>Created a back-up your database. Very Important!!</span></td></tr>
	</table>
	";

    echo '<p>Press the button below to start the upgrade process.</p>';

    echo "<form action='" . $HTTP_SERVER_VARS['PHP_SELF'] . "' method='post'><input type='submit' value='Start Upgrade'><input type='hidden' value='upgrade' name='action'></form>";

    install_footer();

    require_once XOOPS_ROOT_PATH . '/footer.php';
}

//  THIS IS THE UPDATE DATABASE FROM HERE!!!!!!!!! DO NOT TOUCH THIS!!!!!!!!

if ('upgrade' == $action) {
    install_header();

    $error = [];

    $altertable = 'Skipped altering table ';

    $reason = ', field exists in database.';

    $result = $xoopsDB->queryF('SELECT * FROM ' . $xoopsDB->prefix('freecontent_newsticker') . ' ');

    if ($result) {
        $error[] = 'Skipped! Creating table <b>' . $xoopsDB->prefix('freecontent_newsticker') . '</b>, it already exists.';
    } else {
        $xoopsDB->queryF('CREATE TABLE ' . $xoopsDB->prefix('freecontent_newsticker') . " (
        source_url varchar(255) NOT NULL default '',
        headlines text,
        updatetime int(11) default NULL,
        PRIMARY KEY  (source_url)
        ) ENGINE = ISAM");

        $nonerror[] = 'Database <b>' . $xoopsDB->prefix('freecontent_newsticker') . '</b> created.';
    }

    echo "<p>...Updating</p>\n";

    if (count($error)) {
        foreach ($error as $err) {
            echo $err . '<br>';
        }
    }

    if (count($nonerror)) {
        echo "<p><span style='color:#0000FF;font-weight:bold'>Upgrade is succesful, Please update the module by Xoops admin now</span></p>\n";

        foreach ($nonerror as $nonerr) {
            echo $nonerr . '<br>';
        }
    }

    echo "<p><span> <a href=''>Finish update</a></span></p>\n";

    install_footer();

    require_once XOOPS_ROOT_PATH . '/footer.php';
}

// End
?>
