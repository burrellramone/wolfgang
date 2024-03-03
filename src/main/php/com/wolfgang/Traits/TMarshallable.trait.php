<?php
namespace Wolfgang\Traits;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TMarshallable {

    /**
     * @var array
     */
    protected array $marshall_field_exemptions = [];

    /**
     * Gets the fields that are exempted when object is marshalled
     * 
     * @return array An array of fields that are exempted when the object
     * is marshalled
     */
    public function getMarshallFieldExemptions():array {
        return $this->marshall_field_exemptions;
    }

    /**
     * Adds a field that should be extempted when this object is marshalled
     * 
     * @param string The field that should be exempted when this object is marshalled
     * @return void
     */
    public function addMarshallFieldExemption(string $field):void {
        $this->marshall_field_exemptions[$field] = true;
    }
}