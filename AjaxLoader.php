<?php

/**
 * AjaxLoader extension - allows to make ajax-loadable panels on wikipages
 *
 * http://wiki.4intra.net/AjaxLoader
 *
 * Version: 2016-06-01
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @copyright © 2016+ Vitaliy Filippov
 * @licence GNU General Public Licence 2.0 or later
 */

if (!defined('MEDIAWIKI'))
    die('Not an entry point.');

$dir = dirname(__FILE__);

$wgHooks['ParserFirstCallInit'][] = 'wfAjaxLoaderParserFirstCallInit';
$wgAutoloadClasses['AjaxLoader'] = $dir.'/AjaxLoader.body.php';
$wgExtensionMessagesFiles['AjaxLoader'] = $dir.'/AjaxLoader.i18n.php';

$wgResourceModules['AjaxLoader'] = array(
    'scripts'       => 'AjaxLoader.js',
    'localBasePath' => $dir,
    'remoteExtPath' => 'AjaxLoader',
    'messages'      => array(),
);

$wgExtensionCredits['parserhook'][] = array(
    'name'        => 'AjaxLoader',
    'author'      => '[http://wiki.4intra.net/User:VitaliyFilippov User:VitaliyFilippov]',
    'description' => 'Functions to make ajax-loadable panels on wikipages',
    'url'         => 'http://wiki.4intra.net/AjaxLoader',
    'version'     => '2016-06-01',
);

function wfAjaxLoaderParserFirstCallInit($parser)
{
    $parser->setFunctionHook('request', 'wfAjaxLoader_request');
    $parser->setFunctionHook('ajax', 'wfAjaxLoader_ajax');
    return true;
}

/**
 * Return value from the global $_REQUEST array (containing GET/POST variables)
 */
function wfAjaxLoader_request($parser, $param)
{
    global $wgRequest;
    $parser->disableCache();
    return $wgRequest->getText($param);
}

/**
 * Ajax-loadable and closeable panel
 */
function wfAjaxLoader_ajax($parser, $opentext, $closetext, $aftertext, $page, $params)
{
    $parser->getOutput()->addModules('AjaxLoader');
    return $parser->insertStripItem(
        '<span class="ajaxLoadHeaderClosed">'.
        '<a data-closetext="'.htmlspecialchars($closetext).
        '" data-page="'.htmlspecialchars($page).
        '" data-params="'.htmlspecialchars($params).
        '" href="javascript:void(0)" onclick="wfAjaxLoader(this)">').
        $opentext.
        $parser->insertStripItem('</a>').
        ($aftertext ? " $aftertext " : '').
        $parser->insertStripItem('</span><div style="display: none" class="ajaxLoader ajaxLoaderClosed"></div>');
}
