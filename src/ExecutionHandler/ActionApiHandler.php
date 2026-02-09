<?php

namespace MWStake\MediaWiki\Component\MCP\ExecutionHandler;

use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;

/**
 * Call existing API to execute this tool
 */
class ActionApiHandler implements IMcpToolExecutionHandler {

	/**
	 * @param string $action
	 * @param array $defaultParams
	 * @param string $method
	 */
	public function __construct(
		private readonly string $action,
		private readonly array $defaultParams = [],
		private readonly string $method = 'POST'
	) {
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'type' => 'api',
			'apiType' => 'action',
			'defaultParams' => $this->defaultParams,
			'action' => $this->action,
			'method' => $this->method
		];
	}
}
