<?php

namespace MWStake\MediaWiki\Component\MCP;

use JsonSerializable;

class ToolDefinition implements JsonSerializable {

	/**
	 * @param string $title
	 * @param string $description
	 * @param FieldSchema|null $inputSchema
	 * @param FieldSchema $outputSchema
	 */
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
