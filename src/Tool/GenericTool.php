<?php

namespace MWStake\MediaWiki\Component\MCP\Tool;

use MWStake\MediaWiki\Component\MCP\IMcpTool;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;
use MWStake\MediaWiki\Component\MCP\ToolDefinition;

class GenericTool implements IMcpTool {

	/**
	 * @param string $key
	 * @param ToolDefinition $definition
	 * @param IMcpToolExecutionHandler $executionHandler
	 */
	public function __construct(
		private readonly string $key,
		private readonly ToolDefinition $definition,
		private readonly IMcpToolExecutionHandler $executionHandler
	) {
	}

	/**
	 * @return string
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * @return ToolDefinition
	 */
	public function getDefinition(): ToolDefinition {
		return $this->definition;
	}

	/**
	 * @return IMcpToolExecutionHandler
	 */
	public function getExecutionMethod(): IMcpToolExecutionHandler {
		return $this->executionHandler;
	}
}
