<?php

namespace local_integrationtests\fixture;

defined('MOODLE_INTERNAL') || die();

final class oauth2_service_config {

    private const DEFAULT_SERVICES = [
        'nextcloud' => [
            'baseurl' => 'https://nextcloud.test',
            'clientid' => 'testclient',
            'clientsecret' => 'testsecret',
        ],
        'imsobv2p1' => [
            'baseurl' => 'https://ims.test',
        ],
    ];

    /**
     * Get configuration from environment and fallback defaults.
     *
     * @param string $type
     * @return array
     */
    public static function get(string $type): array {
        $type = strtolower($type);

        // Start with defaults if available.
        $config = self::DEFAULT_SERVICES[$type] ?? [];

        // Override/add values from environment.
        $prefix = strtoupper($type) . '_';

        $env = getenv();

        foreach ($env as $name => $value) {
            if (!str_starts_with($name, $prefix)) {
                continue;
            }

            $key = strtolower(substr($name, strlen($prefix)));
            $config[$key] = self::normalize_value($value);
        }

        return $config;
    }

    /**
     * Get a specific value.
     *
     * @param string $type
     * @param string $key
     * @return mixed
     */
    public static function get_value(string $type, string $key): mixed {
        return self::get($type)[$key] ?? null;
    }

    /**
     * Normalize environment values to appropriate PHP types.
     *
     * @param string $value
     * @return mixed
     */
    private static function normalize_value(string $value): mixed {
        $value = trim($value);

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            default => is_numeric($value)
                ? ($value == (int)$value ? (int)$value : (float)$value)
                : $value,
        };
    }
}
