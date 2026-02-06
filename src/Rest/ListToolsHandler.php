<?php

namespace MWStake\MediaWiki\Component\MCP\Rest;

use MediaWiki\Rest\SimpleHandler;
use MWStake\MediaWiki\Component\MCP\ToolRegistry;

class ListToolsHandler extends SimpleHandler {

	/**
	 * @param ToolRegistry $registry
	 */
	public function __construct(
		private readonly ToolRegistry $registry
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
		$tools = [];
		foreach ( $this->registry->getTools() as $tool ) {
			$tools[] = [
				'key' => $tool->getKey(),
				'definition' => $tool->getDefinition(),
				'executionHandler' => $tool->getExecutionMethod(),
			];
		}

		return $this->getResponseFactory()->createJson( $tools );
	}
}