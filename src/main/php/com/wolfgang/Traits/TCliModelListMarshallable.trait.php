<?php
namespace Wolfgang\Traits;

use Stringable;

//Other
use jc21\CliTable;

//Wolfgang
use Wolfgang\Exceptions\IllegalStateException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TCliModelListMarshallable {

	/**
	 *
	 * @throws IllegalStateException
	 * @return void
	 */
	public function climarshall ( ):void {
		$table = new CliTable;
		$table->setTableColor('blue');
		$table->setHeaderColor('cyan');
		$printable_fields = $this->getPrintableFields();

		foreach($printable_fields as $field => $name){
			$table->addField($name, $field, false, 'white');
		}

		$data = array();

		foreach($this as $model_instance){
			$a = array();

			foreach(array_keys($printable_fields) as $field){
				$v = $model_instance->{$field};

				if($v instanceof Stringable){
					$v = (string)$v;
				}

				$a[$field] = $v;
			}

			$data[] = $a;
		}

		$table->injectData($data);
		$table->display();
	}
}