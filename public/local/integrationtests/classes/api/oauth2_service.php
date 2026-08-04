<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_integrationtests\api;

defined('MOODLE_INTERNAL') || die();

use core\oauth2\api;
use core\oauth2\issuer;
use local_integrationtests\fixture\oauth2_service_config;

/**
 * Helper methods for creating OAuth 2 test services.
 *
 * @package    local_integrationtests
 * @copyright  2026 Rex Robert Delacruz <rdelacruz@ubiquitous-tech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class oauth2_service {

    /**
     * Creates or enables a standard OAuth2 issuer.
     *
     * @param string $type
     * @return issuer
     */
    public static function ensure_oauth2_service(string $type): issuer {
        $config = oauth2_service_config::get($type);
        $serviceclassname = "core\\oauth2\\service\\{$type}";

        if (class_exists($serviceclassname) && method_exists($serviceclassname, 'init')) {

            $issuer = $serviceclassname::init();

            foreach ($config as $key => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                try {
                    $issuer->set($key, $value);
                } catch (\coding_exception $e) {
                    debugging(
                        "Ignoring unsupported OAuth2 issuer property '{$key}'",
                        DEBUG_DEVELOPER
                    );
                }
            }

            $issuer->create();

            if (method_exists($serviceclassname, 'create_endpoints')) {
                $serviceclassname::create_endpoints($issuer);
            }

        } else {
            $issuer = new issuer(0, (object) ['name' => $type, 'image' => '',]);

            foreach ($config as $key => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                try {
                    $issuer->set($key, $value);
                } catch (\coding_exception $e) {
                    debugging(
                        "Ignoring unsupported OAuth2 issuer property '{$key}'",
                        DEBUG_DEVELOPER
                    );
                }
            }

            $issuer->create();
        }

        api::enable_issuer($issuer->get('id'));

        return $issuer;
    }
}
