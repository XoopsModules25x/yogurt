<?php declare(strict_types=1);

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
 * Factories of groups
 */
$notesFactory = new Yogurt\NotesHandler($xoopsDB);

$noteId = Request::getInt('note_id', 0, 'POST');

if (1 != Request::getInt('confirm', 0, 'POST')) {
    xoops_confirm(
        [
            'note_id' => $noteId,
            'confirm' => 1,
        ],
        'delete_note.php',
        _MD_YOGURT_ASKCONFIRMNOTEDELETION,
        _MD_YOGURT_CONFIRMNOTEDELETION
    );
} else {
    /**
     * Creating the factory  and the criteria to delete the picture
     * The user must be the owner
     */
    $criteria_note_id = new Criteria(
        'note_id', $noteId
    );
    $uid              = (int)$xoopsUser->getVar('uid');
    $criteriaUid      = new Criteria('note_to', $uid);
    $criteria         = new CriteriaCompo($criteria_note_id);
    $criteria->add($criteriaUid);

    /**
     * Try to delete
     */
    if (1 == $notesFactory->getCount($criteria)) {
        if ($notesFactory->deleteAll($criteria)) {
            redirect_header('notebook.php?uid=' . $uid, 2, _MD_YOGURT_NOTE_DELETED);
        } else {
            redirect_header('notebook.php?uid=' . $uid, 2, _MD_YOGURT_ERROR);
        }
    }
}

require dirname(__DIR__, 2) . '/footer.php';
