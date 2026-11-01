<?php
namespace Wolfgang\Config;

use Wolfgang\Interfaces\IS3ClientConfig;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.5
 */
final class SeaweedFs extends Config implements IS3ClientConfig {
    private static $config = [];

    public function getProtocol(): string {
        if (empty(self::$config)) {
            self::$config = self::getAll();
        }

        return self::$config['protocol'];
    }

    public function getHostname(): string {
        if (empty(self::$config)) {
            self::$config = self::getAll();
        }

        return self::$config['host'];
    }

    public function getPort(): int {
        if (empty(self::$config)) {
            self::$config = self::getAll();
        }

        return self::$config['port'];
    }

    public function getHttpVerify(): bool {
        if (empty(self::$config)) {
            self::$config = self::getAll();
        }

        return self::$config['http_verify'];
    }
}