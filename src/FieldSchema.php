<?php

namespace MWStake\MediaWiki\Component\MCP;

class FieldSchema implements \JsonSerializable {
	public const STRING = 'string';
	public const BOOLEAN = 'boolean';
	public const NUMBER = 'number';
	public const INTEGER = 'integer';
	public const OBJECT = 'object';

	private array $fields = [];
	private array $required = [];

	public function __construct( ?array $fields = [] ) {
		if ( $fields ) {
			$this->fields = $fields;
		}
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @return $this
	 */
	public function addString( string $name, string $description ): self {
		return $this->add( $name, $description, [ 'type' => self::STRING ] );
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @return $this
	 */
	public function addUri( string $name, string $description ): self {
		return $this->add( $name, $description, [ 'type' => self::STRING, 'format' => 'uri' ] );
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @return $this
	 */
	public function addDateTime( string $name, string $description ): self {
		return $this->add( $name, $description, [ 'type' => self::STRING, 'format' => 'date-time' ] );
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @return $this
	 */
	public function addBoolean( string $name, string $description ): self {
		return $this->add( $name, $description, [ 'type' => self::BOOLEAN ] );
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @return $this
	 */
	public function addNumber( string $name, string $description ): self {
		return $this->add( $name, $description, [ 'type' => self::NUMBER ] );
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @return $this
	 */
	public function addInteger( string $name, string $description ): self {
		return $this->add( $name, $description, [ 'type' => self::INTEGER ] );
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @param FieldSchema $schema
	 * @return $this
	 */
	public function addObject( string $name, string $description, FieldSchema $schema ): self {
		return $this->add( $name, $description, $schema->jsonSerialize());
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setNullable( string $name ): self {
		if ( !isset( $this->fields[$name] ) ) {
			return $this;
		}
		if ( is_array( $this->fields[$name]['type'] ) ) {
			if ( !in_array( 'null', $this->fields[$name]['type'] ) ) {
				$this->fields[$name]['type'][] = 'null';
			}
		} else {
			if ( $this->fields[$name]['type'] !== 'null' ) {
				$this->fields[$name]['type'] = [ $this->fields[$name]['type'], 'null' ];
			}
		}
		return $this;
	}

	/**
	 * @param array $fields
	 * @return $this
	 */
	public function setRequired( array $fields ): self {
		$this->required = array_merge( $this->required ?? [], $fields );
		$this->required = array_unique( $this->required );
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $description
	 * @param array $schema
	 * @return $this
	 */
	public function add( string $name, string $description, array $schema ): self {
		$schema['description'] = $description;
		$this->fields[$name] = $schema;
		return $this;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'type' => 'object',
			'properties' => $this->fields
		];
	}
}