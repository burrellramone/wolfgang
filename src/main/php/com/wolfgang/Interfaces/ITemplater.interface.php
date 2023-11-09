<?php

namespace Wolfgang\Interfaces;

interface ITemplater {
	const KIND_SMARTY = 'smarty';
	const KIND_TWIG = 'twig';
	const KIND_VOLT = 'volt';
	const KIND_MUSTACHE = 'mustache';
	const KIND_BLADE = 'blade';

	/**
	 *
	 * @param array $directives
	 */
	public function setLayoutDirectives ( array $directives );

	/**
	 *
	 * @param $template
	 * @return string
	 */
	public function fetch ( $template = null ): string;

	/**
	 */
	public function display ( ): void;

	/**
	 *
	 * @param string $name
	 * @param mixed $val
	 */
	public function assign ( string $name, $val ): void;

	/**
	 *
	 * @param string $layout
	 */
	public function setLayout ( string $layout );

	/**
	 *
	 * @param string $template
	 */
	public function setTemplate ( string $template );

	/**
	 *
	 * @return string|null
	 */
	public function getTemplate ( );
}
