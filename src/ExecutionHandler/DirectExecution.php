<?php

namespace MWStake\MediaWiki\Component\MCP\ExecutionHandler;

use MWStake\MediaWiki\Component\AlertBanners\ObjectFactory;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutor;

class DirectExecution implements IMcpToolExecutionHandler {

	/**
	 * @param array $handlerSpec
	 */
	public function __construct(
		private readonly array $handlerSpec
	) {}

	/**
	 * @param ObjectFactory $objectFactory
	 * @return IMcpToolExecutor
	 */
	public function getHandler( ObjectFactory $objectFactory ): IMcpToolExecutor {
		$object = $objectFactory->newFromSpec( $this->handlerSpec );
		if ( !$object instanceof IMcpToolExecutor ) {
			throw new \InvalidArgumentException( 'Handler spec does not resolve to an IMcpToolExecutor' );
		}
		return $object;
	}

	function jsonSerialize() {
		return [
			'type' => 'direct'
		];
	}
}