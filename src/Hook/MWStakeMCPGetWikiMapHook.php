<?php

namespace MWStake\MediaWiki\Component\MCP\Hook;

interface MWStakeMCPGetWikiMapHook {

	/**
	 * @param array &$map
	 * @return void
	 */
	public function onMWStakeMCPGetWikiMap( &$map ): void;

}
