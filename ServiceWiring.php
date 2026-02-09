<?php

return [
	'MWStake.MCP.ToolRegistry' => static function ( $container ) {
		return new \MWStake\MediaWiki\Component\MCP\ToolRegistry();
	},
];
