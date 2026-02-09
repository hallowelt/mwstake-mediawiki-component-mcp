<?php

namespace MWStake\MediaWiki\Component\MCP\Tests;

use MWStake\MediaWiki\Component\MCP\FieldSchema;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MWStake\MediaWiki\Component\MCP\FieldSchema
 */
class FieldSchemaTest extends TestCase {

	public function testAddString() {
		$schema = new FieldSchema();
		$schema->addString( 'name', 'User name' );

		$result = $schema->jsonSerialize();

		$this->assertArrayHasKey( 'properties', $result );
		$this->assertArrayHasKey( 'name', $result['properties'] );
		$this->assertEquals( 'string', $result['properties']['name']['type'] );
		$this->assertEquals( 'User name', $result['properties']['name']['description'] );
	}

	public function testAddUri() {
		$schema = new FieldSchema();
		$schema->addUri( 'homepage', 'User homepage URL' );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'string', $result['properties']['homepage']['type'] );
		$this->assertEquals( 'uri', $result['properties']['homepage']['format'] );
		$this->assertEquals( 'User homepage URL', $result['properties']['homepage']['description'] );
	}

	public function testAddDateTime() {
		$schema = new FieldSchema();
		$schema->addDateTime( 'createdAt', 'Creation timestamp' );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'string', $result['properties']['createdAt']['type'] );
		$this->assertEquals( 'date-time', $result['properties']['createdAt']['format'] );
	}

	public function testAddBoolean() {
		$schema = new FieldSchema();
		$schema->addBoolean( 'active', 'Is active' );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'boolean', $result['properties']['active']['type'] );
	}

	public function testAddNumber() {
		$schema = new FieldSchema();
		$schema->addNumber( 'price', 'Item price' );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'number', $result['properties']['price']['type'] );
	}

	public function testAddInteger() {
		$schema = new FieldSchema();
		$schema->addInteger( 'count', 'Item count' );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'integer', $result['properties']['count']['type'] );
	}

	public function testAddObject() {
		$innerSchema = new FieldSchema();
		$innerSchema->addString( 'street', 'Street name' );

		$schema = new FieldSchema();
		$schema->addObject( 'address', 'User address', $innerSchema );

		$result = $schema->jsonSerialize();

		$this->assertArrayHasKey( 'address', $result['properties'] );
		$this->assertEquals( 'object', $result['properties']['address']['type'] );
		$this->assertArrayHasKey( 'properties', $result['properties']['address'] );
		$this->assertArrayHasKey( 'street', $result['properties']['address']['properties'] );
	}

	public function testAddArray() {
		$itemSchema = new FieldSchema();
		$itemSchema->addString( 'name', 'Tag name' );

		$schema = new FieldSchema();
		$schema->addArray( 'tags', 'List of tags', $itemSchema );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'array', $result['properties']['tags']['type'] );
		$this->assertArrayHasKey( 'items', $result['properties']['tags'] );
		$this->assertEquals( 'object', $result['properties']['tags']['items']['type'] );
	}

	public function testSetNullable() {
		$schema = new FieldSchema();
		$schema->addString( 'middleName', 'Middle name' )
			->setNullable( 'middleName' );

		$result = $schema->jsonSerialize();

		$this->assertTrue( $result['properties']['middleName']['nullable'] );
	}

	public function testSetNullableNonExistentField() {
		$schema = new FieldSchema();
		$schema->setNullable( 'nonExistent' );

		$result = $schema->jsonSerialize();

		$this->assertArrayNotHasKey( 'nonExistent', $result['properties'] );
	}

	public function testSetRequired() {
		$schema = new FieldSchema();
		$schema->addString( 'firstName', 'First name' )
			->addString( 'lastName', 'Last name' )
			->setRequired( [ 'firstName', 'lastName' ] );

		$result = $schema->jsonSerialize();

		$this->assertContains( 'firstName', $result['required'] );
		$this->assertContains( 'lastName', $result['required'] );
	}

	public function testSetRequiredMultipleCalls() {
		$schema = new FieldSchema();
		$schema->addString( 'field1', 'Field 1' )
			->addString( 'field2', 'Field 2' )
			->addString( 'field3', 'Field 3' )
			->setRequired( [ 'field1' ] )
			->setRequired( [ 'field2', 'field1' ] );

		$result = $schema->jsonSerialize();

		$this->assertCount( 2, $result['required'] );
		$this->assertContains( 'field1', $result['required'] );
		$this->assertContains( 'field2', $result['required'] );
	}

	public function testConstructorWithFields() {
		$fields = [
			'customField' => [
				'type' => 'string',
				'description' => 'Custom field',
				'pattern' => '^[A-Z]+$'
			]
		];

		$schema = new FieldSchema( $fields );
		$result = $schema->jsonSerialize();

		$this->assertArrayHasKey( 'customField', $result['properties'] );
		$this->assertEquals( '^[A-Z]+$', $result['properties']['customField']['pattern'] );
	}

	public function testJsonSerializeStructure() {
		$schema = new FieldSchema();
		$schema->addString( 'name', 'Name' );

		$result = $schema->jsonSerialize();

		$this->assertEquals( 'object', $result['type'] );
		$this->assertArrayHasKey( 'properties', $result );
		$this->assertArrayHasKey( 'required', $result );
		$this->assertFalse( $result['additionalProperties'] );
	}

	public function testChaining() {
		$schema = new FieldSchema();
		$result = $schema
			->addString( 'field1', 'Description 1' )
			->addInteger( 'field2', 'Description 2' )
			->setRequired( [ 'field1' ] );

		$this->assertInstanceOf( FieldSchema::class, $result );
	}
}
