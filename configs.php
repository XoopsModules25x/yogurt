<?php
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

use XoopsModules\Yogurt;

$GLOBALS['xoopsOption']['template_main'] = 'yogurt_configs.tpl';
require __DIR__.'/header.php';

$controler  = new Yogurt\ControlerConfigs($xoopsDB, $xoopsUser);
$nbSections = $controler->getNumbersSections();

if (!$xoopsUser) {
	redirect_header('index.php');
}

/**
 * Factories of tribes
 */
$configsFactory = new Yogurt\ConfigsHandler($xoopsDB);

$uid = (int)$xoopsUser->getVar('uid');

$criteria = new \Criteria('config_uid', $uid);
if ($configsFactory->getCount($criteria) > 0) {
	$configs = $configsFactory->getObjects($criteria);
	$config  = $configs[0];

	$pic  = $config->getVar('pictures');
	$aud  = $config->getVar('audio');
	$vid  = $config->getVar('videos');
	$tri  = $config->getVar('tribes');
	$scr  = $config->getVar('Notes');
	$fri  = $config->getVar('friends');
	$pcon = $config->getVar('profile_contact');
	$pgen = $config->getVar('profile_general');
	$psta = $config->getVar('profile_stats');

	$xoopsTpl->assign('pic', $pic);
	$xoopsTpl->assign('aud', $aud);
	$xoopsTpl->assign('vid', $vid);
	$xoopsTpl->assign('tri', $tri);
	$xoopsTpl->assign('scr', $scr);
	$xoopsTpl->assign('fri', $fri);
	$xoopsTpl->assign('pcon', $pcon);
	$xoopsTpl->assign('pgen', $pgen);
	$xoopsTpl->assign('psta', $psta);
}

//linking style and js
/**
 * Adding to the module js and css of the lightbox and new ones
 */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/yogurt.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/css/jquery.tabs.css');
// what browser they use if IE then add corrective script.
if (preg_match('/msie/', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/css/jquery.tabs-ie.css');
}
//$xoTheme->addStylesheet(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/css/lightbox.css');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/prototype.js');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/scriptaculous.js?load=effects');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/lightbox.js');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/jquery.lightbox-0.3.css');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/jquery.js');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/jquery.lightbox-0.3.js');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/yogurt.js');

//permissions
$xoopsTpl->assign('allow_Notes', $controler->checkPrivilegeBySection('Notes'));
$xoopsTpl->assign('allow_friends', $controler->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_tribes', $controler->checkPrivilegeBySection('tribes'));
$xoopsTpl->assign('allow_pictures', $controler->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos', $controler->checkPrivilegeBySection('videos'));

$xoopsTpl->assign('allow_audios', $controler->checkPrivilegeBySection('audio'));

//form
$xoopsTpl->assign('lang_whocan', _MD_YOGURT_WHOCAN);
$xoopsTpl->assign('lang_configtitle', _MD_YOGURT_CONFIGSTITLE);
$xoopsTpl->assign('lang_configprofilestats', _MD_YOGURT_CONFIGSPROFILESTATS);
$xoopsTpl->assign('lang_configprofilegeneral', _MD_YOGURT_CONFIGSPROFILEGENERAL);
$xoopsTpl->assign('lang_configprofilecontact', _MD_YOGURT_CONFIGSPROFILECONTACT);
$xoopsTpl->assign('lang_configfriends', _MD_YOGURT_CONFIGSFRIENDS);
$xoopsTpl->assign('lang_configNotes', _MD_YOGURT_CONFIGSNOTES);
$xoopsTpl->assign('lang_configsendNotes', _MD_YOGURT_CONFIGSNOTESSEND);
$xoopsTpl->assign('lang_configtribes', _MD_YOGURT_CONFIGSTRIBES);
$xoopsTpl->assign('lang_configaudio', _MD_YOGURT_CONFIGSAUDIOS);
$xoopsTpl->assign('lang_configvideos', _MD_YOGURT_CONFIGSVIDEOS);
$xoopsTpl->assign('lang_configpictures', _MD_YOGURT_CONFIGSPICTURES);
$xoopsTpl->assign('lang_only_me', _MD_YOGURT_CONFIGSONLYME);
$xoopsTpl->assign('lang_only_friends', _MD_YOGURT_CONFIGSONLYEFRIENDS);
$xoopsTpl->assign('lang_only_users', _MD_YOGURT_CONFIGSONLYEUSERS);
$xoopsTpl->assign('lang_everyone', _MD_YOGURT_CONFIGSEVERYONE);

$xoopsTpl->assign('lang_cancel', _MD_YOGURT_CANCEL);

//xoopsToken
$xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());

//Notes
//$xoopsTpl->assign('Notes',$Notes);
$xoopsTpl->assign('lang_answerNote', _MD_YOGURT_ANSWERNOTE);

//Owner data
$xoopsTpl->assign('uid_owner', $controler->uidOwner);
$xoopsTpl->assign('owner_uname', $controler->nameOwner);
$xoopsTpl->assign('isOwner', $controler->isOwner);
$xoopsTpl->assign('isanonym', $controler->isAnonym);

//numbers
$xoopsTpl->assign('nb_tribes', $nbSections['nbTribes']);
$xoopsTpl->assign('nb_photos', $nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos', $nbSections['nbVideos']);
$xoopsTpl->assign('nb_Notes', $nbSections['nbNotes']);
$xoopsTpl->assign('nb_friends', $nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio', $nbSections['nbAudio']);

//navbar
$xoopsTpl->assign('module_name', $xoopsModule->getVar('name'));
$xoopsTpl->assign('lang_mysection', _MD_YOGURT_CONFIGSTITLE);
$xoopsTpl->assign('section_name', _MD_YOGURT_CONFIGSTITLE);
$xoopsTpl->assign('lang_home', _MD_YOGURT_HOME);
$xoopsTpl->assign('lang_photos', _MD_YOGURT_PHOTOS);
$xoopsTpl->assign('lang_friends', _MD_YOGURT_FRIENDS);
$xoopsTpl->assign('lang_audio', _MD_YOGURT_AUDIOS);
$xoopsTpl->assign('lang_videos', _MD_YOGURT_VIDEOS);
$xoopsTpl->assign('lang_Notebook', _MD_YOGURT_NOTEBOOK);
$xoopsTpl->assign('lang_profile', _MD_YOGURT_PROFILE);
$xoopsTpl->assign('lang_tribes', _MD_YOGURT_TRIBES);
$xoopsTpl->assign('lang_configs', _MD_YOGURT_CONFIGSTITLE);

//xoopsToken
$xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());

include __DIR__.'/../../footer.php';