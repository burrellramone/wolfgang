<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.5
 */
interface IS3ClientConfig {
    public function getProtocol(): string;
    public function getHostname(): string;
    public function getPort(): int;
    public function getHttpVerify(): bool;
}
