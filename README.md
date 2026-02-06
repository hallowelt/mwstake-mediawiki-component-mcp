# MCP server companion

This component provides tool definitions and execution ability to the MCP server.
It is used by the MCP server to retrieve tool definitions and execute them, allowing tools to be defined
in MediaWiki extensions and used by the MCP server
(which is very hard and inconvenient to do in the MCP server itself, as it is a Node.js application).

## Setup on wiki side

To set up the component, add the following to your `LocalSettings.php` file:

```php
$GLOBALS['mwsgTokenAuthenticatorServiceAllowedRestPaths'] = $GLOBALS['mwsgTokenAuthenticatorServiceAllowedRestPaths'] ?? [];

$GLOBALS['mwsgTokenAuthenticatorServiceAllowedRestPaths'][] = '/mws/v1/mcp/get_wiki_map';
$GLOBALS['mwsgTokenAuthenticatorServiceAllowedRestPaths'][] = '/mws/v1/app-token/generate';
$GLOBALS['mwsgTokenAuthenticatorServiceAllowedRestPaths'][] = '/mws/v1/mcp/list_tools';

```

## Adding tools

Create a class defining your tool

```php

use MWStake\MediaWiki\Component\MCP\ExecutionHandler\RestApiHandler;
use MWStake\MediaWiki\Component\MCP\FieldSchema;
use MWStake\MediaWiki\Component\MCP\IMcpTool;
use MWStake\MediaWiki\Component\MCP\IMcpToolExecutionHandler;
use MWStake\MediaWiki\Component\MCP\ToolDefinition;

class CreatePage implements IMcpTool {

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
			->addString( 'key', 'DB-key of the page that was just created' );

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
		return new RestApiHandler( '/v1/page/', 'POST' );
	}
}
```

Or, specify is as a generic tool (no custom class)

```php
$inputSchema = ( new FieldSchema() )
    ->addString( 'source', 'Text, content of the page' )
    ->addString( 'title', 'Title of the page' )
    ->addString( 'comment', 'Summary of the edit. Can be null' )
    ->setNullable( 'comment' )
    ->setRequired( [ 'source', 'title', 'comment' ] );

$outputSchema = ( new FieldSchema() )
    ->addInteger( 'id', 'ID of the page that was just created' )
    ->addString( 'title', 'Title of the page that was just created' )
    ->addString( 'key', 'DB-key of the page that was just created' );

$definition = new ToolDefinition(
    'Create a wiki page',
    'Create aage with given content',
    $inputSchema,
    $outputSchema
);

$tool = new MWStake\MediaWiki\Component\MCP\Tool\GenericTool(
    'createPage',
    $definition,
    new RestApiHandler( '/v1/page/', 'POST' )
);
```

Then, register the tool in the DI container:

```php

function onMediaWikiServices( $services ) {
    $registry = $services->getService( 'MWStake.MCP.ToolRegistry' );
    $registry->registerTool( $tool );
}
```

### Execution methods

As seen above, tool returns `IMcpToolExecutionHandler`. This defines how this tool should be executed.
Tools can execute a regular, already existing API

```php
return new RestApiHandler( '/v1/page/', 'POST' );
return new ActionApiHandler( 'edit' )
```

Or they can define a custom handler for the tool

```php

class MyCustomToolExecutor implement IMcpToolExecutor {
    public function execute( IMcpTool $tool, array $input ): array {
        // Process $input (which will be in format as specified by tool's input schema) and execute the tool's action
        // Return output in format specified by tool's output schema
    }
}
```

in which case, provide ObjectFactory spec for your custom executor in the `getExecutionMethod` method of the tool

```php
return new DirectExecution( [
    'class' => MyCustomToolExecutor::class,
    'args' => ...
] );
```
