## Call MCP server to list tools

```bash
curl -i -X POST http://localhost:3336/mcp \
      -H "Content-Type: application/json" \
      -H "Accept: application/json, text/event-stream" \
      --data '{
    "jsonrpc": "2.0",
    "id": 1,
    "method": "tools/list"
  }'
```

## Common use statements

```php
use MWStake\MediaWiki\Component\MCP\ExecutionHandler\ActionApiHandler;
use MWStake\MediaWiki\Component\MCP\ExecutionHandler\RestApiHandler;
use MWStake\MediaWiki\Component\MCP\FieldSchema;
use MWStake\MediaWiki\Component\MCP\ToolDefinition;
```

## Param notes
- All tools must have a `wiki_id` parameter in their input schema, which is the ID of the wiki to run the tool on.
This is required for the tool to know which wiki to run on, and to authenticate the request.
It will be added automatically, tool does not need to define it, not support it in any way

## Action API tool with nested response
```php
$outputSchema = ( new FieldSchema() )
	->addObject( 'query', 'Query result of the API call',
		( new FieldSchema() )
			->addArray( 'allcategories', 'List of all categories',
				( new FieldSchema() )
					->addInteger( 'size', 'Total member count' )
					->addInteger( 'pages', 'Number of pages in this category' )
					->addInteger( 'files', 'Number of files in this category' )
					->addInteger( 'subcats', 'Number of subcategories in this category' )
					->addString( '*', 'Name of the category' )
			)
	);

$definition = new ToolDefinition(
	'List categories',
	'List all categories declared on the wiki',
	null,
	$outputSchema
);

$tool = new MWStake\MediaWiki\Component\MCP\Tool\GenericTool(
	'listCategories',
	$definition,
	new ActionApiHandler( 'query', [
		'list' => 'allcategories',
		'format' => 'json',
	], 'GET' )
);
```

### Call this tool
```bash
curl -i -X POST http://localhost:3336/mcp \
	  -H "Content-Type: application/json" \
	  -H "Accept: application/json, text/event-stream" \
	  --data '{
	"jsonrpc": "2.0",
	"id": 2,
	"method": "tools/call",
	"params": {
	  "name": "listCategories",
	  "arguments": {
		"wiki_id":"wiki_0364e87d"
	  }
	}
  }'
```

## Action API - POST
```php
$inputSchema = ( new FieldSchema() )
	->addString( 'title', 'Title of the page to edit' )
	->addString( 'text', 'New content of the page' )
	->addString( 'summary', 'Summary of the edit' );

$outputSchema = ( new FieldSchema() )
	->addObject( 'edit', 'Result of the edit',
		( new FieldSchema() )
			->addString( 'result', 'Result of the edit action' )
			->addInteger( 'pageid', 'ID of the edited page' )
			->addString( 'title', 'Title of the edited page' )
			->addString( 'contentmodel', 'Content model of the edited page' )
			->addString( 'oldrevid', 'Revision ID of the page before the edit' )
			->addString( 'newrevid', 'Revision ID of the page after the edit' )
	);

$definition = new ToolDefinition(
	'Create a wiki page',
	'Create page with given content',
	$inputSchema,
	$outputSchema
);

$tool = new MWStake\MediaWiki\Component\MCP\Tool\GenericTool(
	'createPage',
	$definition,
	new ActionApiHandler( 'edit', [], 'POST' )
);
```

### Call this tool
```bash
curl -i -X POST http://localhost:3336/mcp \
      -H "Content-Type: application/json" \
      -H "Accept: application/json, text/event-stream" \
      --data '{
    "jsonrpc": "2.0",
    "id": 2,
    "method": "tools/call",
    "params": {
      "name": "createPageActionApi",
      "arguments": {
        "text": "This is the content of the page",
        "title": "My Test Page2",
        "summary": "initial creation","wiki_id":"wiki_0364e87d"
      }
    }
  }'
```

## REST API tool with simple response

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
	->addString( 'key', 'DB-key of the page that was just created' )
	->setRequired( [ 'id', 'title', 'key' ] );

$definition = new ToolDefinition(
	'Create a wiki page',
	'Create aage with given content',
	$inputSchema,
	$outputSchema
);

$tool = new MWStake\MediaWiki\Component\MCP\Tool\GenericTool(
	'createPage',
	$definition,
	new RestApiHandler( '/v1/page', 'POST' )
);
```

### Call this tool
```bash
curl -i -X POST http://localhost:3336/mcp \
	  -H "Content-Type: application/json" \
	  -H "Accept: application/json, text/event-stream" \
	  --data '{
	"jsonrpc": "2.0",
	"id": 2,
	"method": "tools/call",
	"params": {
	  "name": "createPage",
	  "arguments": {
		"source": "This is the content of the page",
		"title": "My Test Page",
		"comment": "initial creation","wiki_id":"wiki_0364e87d"
	  }
	}
  }'
```

## Direct execution 

### Declare handler

```php

class TestHandler implements IMcpToolExecutor {

	public function execute( IMcpTool $tool, array $input ): array {
		error_log( var_export( $input , 1));

		return [ 'myOutput' => 'This is a test' ];
	}
}
```

### Declare tool
```php
$inputSchema = ( new FieldSchema() )
    ->addString( 'myInput', 'Test input' );

$outputSchema = ( new FieldSchema() )
    ->addString( 'myOutput', 'Test output' );

$definition = new ToolDefinition(
    'Test tool for direct MCP execution',
    'Test',
    $inputSchema,
    $outputSchema
);

$tool = new MWStake\MediaWiki\Component\MCP\Tool\GenericTool(
    'testTool',
    $definition,
    new \MWStake\MediaWiki\Component\MCP\ExecutionHandler\DirectExecution( [
        'class' => \MWStake\MediaWiki\Component\MCP\Tool\TestHandler::class
    ] )
);
```

### Call this tool
```bash
curl -i -X POST http://localhost:3336/mcp \
      -H "Content-Type: application/json" \
      -H "Accept: application/json, text/event-stream" \
      --data '{
    "jsonrpc": "2.0",
    "id": 2,
    "method": "tools/call",
    "params": {
      "name": "testTool",
      "arguments": {
        "myInput": "This is an input","wiki_id":"wiki_0364e87d"
      }
    }
  }'
```
