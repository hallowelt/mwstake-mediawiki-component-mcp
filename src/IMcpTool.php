<?php

namespace MWStake\MediaWiki\Component\MCP;

interface IMcpTool {

	/**
	 * @return string
	 */
	public function getKey(): string;

	/**
	 * @return ToolDefinition
	 */
	public function getDefinition(): ToolDefinition;

	/**
	 * @return IMcpToolExecutionHandler
	 */
	public function getExecutionMethod(): IMcpToolExecutionHandler;
}
