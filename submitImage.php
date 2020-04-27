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

$GLOBALS['xoopsOption']['template_main'] = 'yogurt_index.tpl';
require __DIR__ . '/header.php';

//require_once dirname(dirname(__DIR__)) . '/header.php';

/**
 * Modules class includes
 */
//require_once __DIR__ . '/class/Image.php';

/**
 * Factory of pictures created
 */
$imageFactory = new Yogurt\ImageHandler($xoopsDB);

/**
 * Getting the title
 */
$title   = Request::getString('title', '', 'POST');
$caption = Request::getString('caption', '', 'POST');
/**
 * Getting parameters defined in admin side
 */
$path_upload   = XOOPS_ROOT_PATH . '/uploads/yogurt/images';
$pictwidth     = $helper->getConfig('resized_width');
$pictheight    = $helper->getConfig('resized_height');
$thumbwidth    = $helper->getConfig('thumb_width');
$thumbheight   = $helper->getConfig('thumb_height');
$maxfilebytes  = $helper->getConfig('maxfilesize');
$maxfileheight = $helper->getConfig('max_original_height');
$maxfilewidth  = $helper->getConfig('max_original_width');

/**
 * If we are receiving a file
 */
if ('sel_photo' === Request::getArray('xoops_upload_file', '', 'POST')[0]) {
    /**
     * Verify Token
     */
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 3, _MD_YOGURT_TOKENEXPIRED);
    }
    ini_set('memory_limit', '50M');
    /**
     * Try to upload picture resize it insert in database and then redirect to index
     */
    if ($imageFactory->receivePicture(
        $title,
        $caption,
        $path_upload,
        $thumbwidth,
        $thumbheight,
        $pictwidth,
        $pictheight,
        $maxfilebytes,
        $maxfilewidth,
        $maxfileheight
    )) {
        $extra_tags['X_OWNER_NAME'] = $xoopsUser->getVar('uname');
        $extra_tags['X_OWNER_UID']  = $xoopsUser->getVar('uid');
        /** @var \XoopsNotificationHandler $notificationHandler */
        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('picture', $xoopsUser->getVar('uid'), 'new_picture', $extra_tags);
        //header("Location: ".XOOPS_URL."/modules/yogurt/index.php?uid=".$xoopsUser->getVar('uid'));
        redirect_header(
            XOOPS_URL . '/modules/yogurt/album.php?uid=' . $xoopsUser->getVar('uid'),
            3,
            _MD_YOGURT_UPLOADED
        );
    } else {
        redirect_header(
            XOOPS_URL . '/modules/yogurt/album.php?uid=' . $xoopsUser->getVar('uid'),
            3,
            _MD_YOGURT_ERROR
        );
    }
}

/**
 * Close page
 */
require dirname(__DIR__, 2) . '/footer.php';
