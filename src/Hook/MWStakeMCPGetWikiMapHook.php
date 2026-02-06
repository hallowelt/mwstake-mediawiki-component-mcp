<?php

namespace MWStake\MediaWiki\Component\MCP\Hook;

interface MWStakeMCPGetWikiMapHook {

	/**
	 * @return array
	 */
	public function onMWStakeMCPGetWikiMap( &$map ): array;

}