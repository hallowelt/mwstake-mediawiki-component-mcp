<?php

namespace MWStake\MediaWiki\Component\MCP\ExecutionHandler;

use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;

/**
 * Call existing API to execute this tool
 */
class RestApiHandler implements IMcpToolExecutionHandler {

	/**
	 * @param string $path
	 * @param string $method
	 * @param array $headers
	 */
	public function __construct(
		private readonly string $path,
		private readonly string $method = 'POST',
		private readonly array $headers = [
			'Content-Type' => 'application/json'
		]
	) {
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'type' => 'api',
			'apiType' => 'rest',
			'path' => $this->path,
			'method' => $this->method,
			'headers' => $this->headers
		];
	}
}
