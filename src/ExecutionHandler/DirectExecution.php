<?php

namespace MWStake\MediaWiki\Component\MCP\ExecutionHandler;

use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutor;
use Wikimedia\ObjectFactory\ObjectFactory;

class DirectExecution implements IMcpToolExecutionHandler {

	/**
	 * @param array $handlerSpec
	 */
	public function __construct(
		private readonly array $handlerSpec
	) {
	}

	/**
	 * @param ObjectFactory $objectFactory
	 * @return IMcpToolExecutor
	 */
	public function getHandler( ObjectFactory $objectFactory ): IMcpToolExecutor {
		$object = $objectFactory->createObject( $this->handlerSpec );
		if ( !$object instanceof IMcpToolExecutor ) {
			throw new \InvalidArgumentException( 'Handler spec does not resolve to an IMcpToolExecutor' );
		}
		return $object;
	}

	public function jsonSerialize() {
		return [
			'type' => 'direct'
		];
	}
}
