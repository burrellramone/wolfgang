<?php

namespace Wolfgang\Interfaces\Network;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 */
interface IDsn extends IUri {
	public function getDiver ( ): string;
	public function getUsername ( ): string;
	public function getPassword ( ): string;
	public function getDatabase ( ): string;
}
