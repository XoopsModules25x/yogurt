<?php

declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Marcello Brandão aka  Suico
 * @author       XOOPS Development Team
 * @since
 */

use Xmf\Request;
use XoopsModules\Yogurt;

require __DIR__ . '/header.php';

/**
 * Verify Token
 */
//if (!($GLOBALS['xoopsSecurity']->check())){
//            redirect_header(\Xmf\Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _MD_YOGURT_TOKENEXPIRED);
//}

/**
 * Receiving info from get parameters
 */
$relgroupuser_id = Request::getInt('relgroup_id', 0, 'POST');
$group_id        = Request::getInt('group_id', 0, 'POST');

if (!isset($_POST['confirm']) || 1 !== Request::getInt('confirm', 0, 'POST')) {
    xoops_confirm(
        [
            'relgroup_id' => $relgroupuser_id,
            'group_id'    => $group_id,
            'confirm'     => 1,
        ],
        'abandongroup.php',
        _MD_YOGURT_ASKCONFIRMABANDONGROUP,
        _MD_YOGURT_CONFIRMABANDON
    );
} else {
    /**
     * Creating the factory  and the criteria to delete the picture
     * The user must be the owner
     */
    $relgroupuserFactory = new Yogurt\RelgroupuserHandler(
        $xoopsDB
    );
    $criteria_rel_id     = new Criteria('rel_id', $relgroupuser_id);
    $uid                 = (int)$xoopsUser->getVar('uid');
    $criteriaUid         = new Criteria('rel_user_uid', $uid);
    $criteria            = new CriteriaCompo($criteria_rel_id);
    $criteria->add($criteriaUid);

    /**
     * Try to delete
     */
    if ($relgroupuserFactory->deleteAll($criteria)) {
        redirect_header('group.php?group_id=' . $group_id . '', 1, _MD_YOGURT_GROUPABANDONED);
    } else {
        redirect_header('group.php?group_id=' . $group_id . '', 1, _MD_YOGURT_ERROR);
    }
}
require dirname(__DIR__, 2) . '/footer.php';
