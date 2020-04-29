<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package             profile
 * @since               2.3.0
 * @author              Jan Pedersen
 * @author              Taiwen Jiang <phppp@users.sourceforge.net>
 */

use XoopsModules\Yogurt\IndexController;

$GLOBALS['xoopsOption']['template_main'] = 'yogurt_changepass.tpl';
require __DIR__ . '/header.php';

/**
 * Fetching numbers of groups friends videos pictures etc...
 */
$controller = new IndexController($xoopsDB, $xoopsUser, $xoopsModule);
$nbSections = $controller->getNumbersSections();

if (!$GLOBALS['xoopsUser']) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}

$xoopsOption['xoops_pagetitle'] = sprintf(_MD_YOGURT_CHANGEPASSWORD, $xoopsModule->getVar('name'), $controller->nameOwner);

if (!isset($_POST['submit'])) {
    //show change password form
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    $form = new XoopsThemeForm(_MD_YOGURT_CHANGEPASSWORD, 'form', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormPassword(_MD_YOGURT_OLDPASSWORD, 'oldpass', 15, 50), true);
    $form->addElement(new XoopsFormPassword(_MD_YOGURT_NEWPASSWORD, 'newpass', 15, 50), true);
    $form->addElement(new XoopsFormPassword(_US_VERIFYPASS, 'vpass', 15, 50), true);
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->assign($GLOBALS['xoopsTpl']);

    $xoBreadcrumbs[] = ['title' => _MD_YOGURT_CHANGEPASSWORD];
} else {
    /* @var XoopsConfigHandler $configHandler */
    $configHandler             = xoops_getHandler('config');
    $GLOBALS['xoopsConfigUser'] = $configHandler->getConfigsByCat(XOOPS_CONF_USER);
    $myts                       = MyTextSanitizer::getInstance();
    $oldpass                    = @$myts->stripSlashesGPC(trim($_POST['oldpass']));
    $password                   = @$myts->stripSlashesGPC(trim($_POST['newpass']));
    $vpass                      = @$myts->stripSlashesGPC(trim($_POST['vpass']));
    $errors                     = [];
    if (!password_verify($oldpass, $GLOBALS['xoopsUser']->getVar('pass', 'n'))) {
        $errors[] = _MD_YOGURT_WRONGPASSWORD;
    }
    if (mb_strlen($password) < $GLOBALS['xoopsConfigUser']['minpass']) {
        $errors[] = sprintf(_US_PWDTOOSHORT, $GLOBALS['xoopsConfigUser']['minpass']);
    }
    if ($password != $vpass) {
        $errors[] = _US_PASSNOTSAME;
    }

    if ($errors) {
        $msg = implode('<br>', $errors);
    } else {
        //update password
        $GLOBALS['xoopsUser']->setVar('pass', password_hash($password, PASSWORD_DEFAULT));
        /* @var XoopsMemberHandler $memberHandler */
        $memberHandler = xoops_getHandler('member');
        $msg           = _MD_YOGURT_ERRORDURINGSAVE;
        if ($memberHandler->insertUser($GLOBALS['xoopsUser'])) {
            $msg = _MD_YOGURT_PASSWORDCHANGED;
        }
    }
    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/index.php?uid=' . $GLOBALS['xoopsUser']->getVar('uid'), 2, $msg);
}

require __DIR__ . '/footer.php';
require dirname(__DIR__, 2) . '/footer.php';