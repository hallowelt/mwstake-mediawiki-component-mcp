<?php

namespace MWStake\MediaWiki\Component\MCP\Tests;

use MWStake\MediaWiki\Component\MCP\IMcpTool;
use MWStake\MediaWiki\Component\MCP\ToolRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MWStake\MediaWiki\Component\MCP\ToolRegistry
 */
class ToolRegistryTest extends TestCase {

	private function createMockTool( string $key ): IMcpTool {
		$tool = $this->createMock( IMcpTool::class );
		$tool->method( 'getKey' )->willReturn( $key );
		return $tool;
	}

	public function testRegisterTool() {
		$registry = new ToolRegistry();
		$tool = $this->createMockTool( 'testTool' );

		$registry->registerTool( $tool );

		$this->assertSame( $tool, $registry->getTool( 'testTool' ) );
	}

	public function testGetTools() {
		$registry = new ToolRegistry();
		$tool1 = $this->createMockTool( 'tool1' );
		$tool2 = $this->createMockTool( 'tool2' );

		$registry->registerTool( $tool1 );
		$registry->registerTool( $tool2 );

		$tools = $registry->getTools();

		$this->assertCount( 2, $tools );
		$this->assertSame( $tool1, $tools['tool1'] );
		$this->assertSame( $tool2, $tools['tool2'] );
	}

	public function testGetToolReturnsNullForNonExistent() {
		$registry = new ToolRegistry();

		$this->assertNull( $registry->getTool( 'nonExistent' ) );
	}

	public function testRegisterToolOverwritesExisting() {
		$registry = new ToolRegistry();
		$tool1 = $this->createMockTool( 'sameTool' );
		$tool2 = $this->createMockTool( 'sameTool' );

		$registry->registerTool( $tool1 );
		$registry->registerTool( $tool2 );

		$this->assertSame( $tool2, $registry->getTool( 'sameTool' ) );
		$this->assertCount( 1, $registry->getTools() );
	}

	public function testGetToolsReturnsEmptyArray() {
		$registry = new ToolRegistry();

		$this->assertIsArray( $registry->getTools() );
		$this->assertEmpty( $registry->getTools() );
	}

	public function testGetToolsReturnsCorrectKeys() {
		$registry = new ToolRegistry();
		$tool1 = $this->createMockTool( 'createPage' );
		$tool2 = $this->createMockTool( 'editPage' );

		$registry->registerTool( $tool1 );
		$registry->registerTool( $tool2 );

		$tools = $registry->getTools();

		$this->assertArrayHasKey( 'createPage', $tools );
		$this->assertArrayHasKey( 'editPage', $tools );
	}
}
