<?php

namespace MWStake\MediaWiki\Component\MCP;

class ToolRegistry {

	/** @var IMcpTool[] */
	private array $tools = [];

	/**
	 * @param IMcpTool $tool
	 * @return void
	 */
	public function registerTool( IMcpTool $tool ): void {
		$this->tools[$tool->getKey()] = $tool;
	}

	/**
	 * @return IMcpTool[]
	 */
	public function getTools(): array {
		return $this->tools;
	}

	/**
	 * @param string $key
	 * @return IMcpTool|null
	 */
	public function getTool( string $key ): ?IMcpTool {
		return $this->tools[$key] ?? null;
	}
}
