<?php

namespace MWStake\MediaWiki\Component\MCP\Rest;

use Config;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\WikiMap\WikiMap;

class GetWikiMapHandler extends SimpleHandler {

	/**
	 * @param HookContainer $hookContainer
	 * @param Config $config
	 */
	public function __construct(
		private readonly HookContainer $hookContainer,
		private readonly Config $config
	) {
	}

	/**
	 * @return true
	 */
	public function needsReadAccess() {
		return true;
	}

	/**
	 * @return array|mixed
	 */
	public function execute() {
		$map = [
			WikiMap::getCurrentWikiId() => $this->config->get( 'ScriptPath' )
		];
		$this->hookContainer->run( 'MWStakeMCPGetWikiMap', [ &$map ] );

		return $map;
	}
}
