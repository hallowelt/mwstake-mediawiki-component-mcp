<?php

namespace MWStake\MediaWiki\Component\MCP;

interface IMcpTool{

	public function getKey(): string;

	public function getDefinition(): ToolDefinition;

	public function getExecutionMethod(): IMcpToolExecutionHandler;
}