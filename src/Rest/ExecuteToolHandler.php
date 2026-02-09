<?php

namespace MWStake\MediaWiki\Component\MCP\Rest;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MWStake\MediaWiki\Component\MCP\ExecutionHandler\DirectExecution;
use MWStake\MediaWiki\Component\MCP\ToolRegistry;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\ParamValidator\ParamValidator;

class ExecuteToolHandler extends SimpleHandler {

	/**
	 * @param ToolRegistry $registry
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct(
		private readonly ToolRegistry $registry,
		private readonly ObjectFactory $objectFactory
	) {
	}

	/**
	 * @return true
	 */
	public function needsReadAccess() {
		return true;
	}

	/**
	 * @return Response|mixed
	 * @throws HttpException
	 */
	public function execute() {
		$params = $this->getValidatedBody();
		$tool = $this->registry->getTool( $params['toolKey'] );
		if ( !$tool ) {
			throw new HttpException( "Tool with key {$params['toolKey']} not found", 404 );
		}
		$execution = $tool->getExecutionMethod();
		if ( !( $execution instanceof DirectExecution ) ) {
			throw new HttpException( "Tool with key {$params['toolKey']} cannot be executed directly", 400 );
		}
		$executor = $execution->getHandler( $this->objectFactory );
		try {
			$res = $executor->execute( $tool, $params['args'] ?? [] );
		} catch ( \Exception $e ) {
			throw new HttpException(
				"Execution of tool with key {$params['toolKey']} failed: " . $e->getMessage(),
				500
			);
		}
		return $this->getResponseFactory()->createJson( $res );
	}

	/**
	 * @return array[]
	 */
	public function getBodyParamSettings(): array {
		return [
			'toolKey' => [
				static::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'args' => [
				static::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'array',
				ParamValidator::PARAM_REQUIRED => false,
			],
		];
	}
}
