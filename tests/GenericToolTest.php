<?php

namespace MWStake\MediaWiki\Component\MCP\Tests;

use MWStake\MediaWiki\Component\MCP\FieldSchema;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;
use MWStake\MediaWiki\Component\MCP\Tool\GenericTool;
use MWStake\MediaWiki\Component\MCP\ToolDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MWStake\MediaWiki\Component\MCP\Tool\GenericTool
 */
class GenericToolTest extends TestCase {

	public function testGetKey() {
		$definition = $this->createMock( ToolDefinition::class );
		$executionHandler = $this->createMock( IMcpToolExecutionHandler::class );

		$tool = new GenericTool( 'myTool', $definition, $executionHandler );

		$this->assertEquals( 'myTool', $tool->getKey() );
	}

	public function testGetDefinition() {
		$definition = $this->createMock( ToolDefinition::class );
		$executionHandler = $this->createMock( IMcpToolExecutionHandler::class );

		$tool = new GenericTool( 'myTool', $definition, $executionHandler );

		$this->assertSame( $definition, $tool->getDefinition() );
	}

	public function testGetExecutionMethod() {
		$definition = $this->createMock( ToolDefinition::class );
		$executionHandler = $this->createMock( IMcpToolExecutionHandler::class );

		$tool = new GenericTool( 'myTool', $definition, $executionHandler );

		$this->assertSame( $executionHandler, $tool->getExecutionMethod() );
	}

	public function testFullIntegration() {
		$inputSchema = new FieldSchema();
		$inputSchema->addString( 'title', 'Page title' )
			->setRequired( [ 'title' ] );

		$outputSchema = new FieldSchema();
		$outputSchema->addInteger( 'id', 'Page ID' );

		$definition = new ToolDefinition(
			'Create Page',
			'Creates a new page',
			$inputSchema,
			$outputSchema
		);

		$executionHandler = $this->createMock( IMcpToolExecutionHandler::class );

		$tool = new GenericTool( 'createPage', $definition, $executionHandler );

		$this->assertEquals( 'createPage', $tool->getKey() );
		$this->assertSame( $definition, $tool->getDefinition() );
		$this->assertSame( $executionHandler, $tool->getExecutionMethod() );

		$serialized = $definition->jsonSerialize();
		$this->assertEquals( 'Create Page', $serialized['title'] );
		$this->assertEquals( 'Creates a new page', $serialized['description'] );
	}
}
