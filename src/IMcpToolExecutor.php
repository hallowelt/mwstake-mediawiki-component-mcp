<?php

namespace MWStake\MediaWiki\Component\MCP;

interface IMcpToolExecutor {

	/**
	 * @param IMcpTool $tool
	 * @param array $input
	 * @return array
	 */
	public function execute( IMcpTool $tool, array $input ): array;
}