<?php

namespace MWStake\MediaWiki\Component\MCP\Tool;

use MWStake\MediaWiki\Component\MCP\ExecutionHandler\RestApiHandler;
use MWStake\MediaWiki\Component\MCP\FieldSchema;
use MWStake\MediaWiki\Component\MCP\IMcpTool;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;
use MWStake\MediaWiki\Component\MCP\ToolDefinition;

class CreatePage implements IMcpTool {

	/**
	 * @return string
	 */
	public function getKey(): string {
		return 'createPage';
	}

	/**
	 * @return ToolDefinition
	 */
	public function getDefinition(): ToolDefinition {
		$inputSchema = ( new FieldSchema() )
			->addString( 'source', 'Text, content of the page' )
			->addString( 'title', 'Title of the page' )
			->addString( 'comment', 'Summary of the edit. Can be null' )
			->setNullable( 'comment' )
			->setRequired( [ 'source', 'title', 'comment' ] );

		$outputSchema = ( new FieldSchema() )
			->addInteger( 'id', 'ID of the page that was just created' )
			->addString( 'title', 'Title of the page that was just created' )
			->addString( 'key', 'DB-key of the page that was just created' )
			->setRequired( [ 'id', 'title', 'key' ] );

		return new ToolDefinition(
			'Create a wiki page',
			'Create aage with given content',
			$inputSchema,
			$outputSchema
		);
	}

	/**
	 * @return IMcpToolExecutionHandler
	 */
	public function getExecutionMethod(): IMcpToolExecutionHandler {
		return new RestApiHandler( '/v1/page', 'POST' );
	}
}
