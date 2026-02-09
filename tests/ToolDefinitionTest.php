<?php

namespace MWStake\MediaWiki\Component\MCP\Tests;

use MWStake\MediaWiki\Component\MCP\FieldSchema;
use MWStake\MediaWiki\Component\MCP\ToolDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MWStake\MediaWiki\Component\MCP\ToolDefinition
 */
class ToolDefinitionTest extends TestCase {

	public function testConstructorAndJsonSerialize() {
		$inputSchema = new FieldSchema();
		$inputSchema->addString( 'title', 'Page title' );

		$outputSchema = new FieldSchema();
		$outputSchema->addInteger( 'id', 'Page ID' );

		$definition = new ToolDefinition(
			'Create Page',
			'Creates a new wiki page',
			$inputSchema,
			$outputSchema
		);

		$result = $definition->jsonSerialize();

		$this->assertEquals( 'Create Page', $result['title'] );
		$this->assertEquals( 'Creates a new wiki page', $result['description'] );
		$this->assertSame( $inputSchema, $result['inputSchema'] );
		$this->assertSame( $outputSchema, $result['outputSchema'] );
	}

	public function testWithNullInputSchema() {
		$outputSchema = new FieldSchema();
		$outputSchema->addString( 'result', 'Operation result' );

		$definition = new ToolDefinition(
			'Simple Tool',
			'A tool with no input',
			null,
			$outputSchema
		);

		$result = $definition->jsonSerialize();

		$this->assertNull( $result['inputSchema'] );
		$this->assertSame( $outputSchema, $result['outputSchema'] );
	}

	public function testJsonSerializeReturnsArray() {
		$outputSchema = new FieldSchema();

		$definition = new ToolDefinition(
			'Test Tool',
			'Test description',
			null,
			$outputSchema
		);

		$result = $definition->jsonSerialize();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'title', $result );
		$this->assertArrayHasKey( 'description', $result );
		$this->assertArrayHasKey( 'inputSchema', $result );
		$this->assertArrayHasKey( 'outputSchema', $result );
	}
}
