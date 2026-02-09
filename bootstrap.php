<?php

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\MCP\Tool\CreatePage;

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_MCP_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_MCP_VERSION', '1.0.0' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
->register( 'mcp', static function () {
	$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/ServiceWiring.php';

	$restFilePath = wfRelativePath( __DIR__ . '/rest-routes.json', $GLOBALS['IP'] );
	$GLOBALS['wgRestAPIAdditionalRouteFiles'][] = $restFilePath;

	$GLOBALS['wgHooks']['MediaWikiServices'][] = static function ( MediaWikiServices $services ) {
		$registry = $services->getService( 'MWStake.MCP.ToolRegistry' );
		$registry->registerTool( new CreatePage() );
	};
} );
