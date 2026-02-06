<?php

namespace MWStake\MediaWiki\Component\MCP;

class ToolDefinition implements \JsonSerializable {

	public function __construct(
		private readonly string $title,
		private readonly string $description,
		private readonly ?FieldSchema $inputSchema,
		private readonly FieldSchema $outputSchema
	) {
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'title' => $this->title,
			'description' => $this->description,
			'inputSchema' => $this->inputSchema,
			'outputSchema' => $this->outputSchema
		];
	}

}