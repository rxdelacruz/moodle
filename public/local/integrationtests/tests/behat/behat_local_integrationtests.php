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

/**
 * Behat step definitions for local integration tests.
 *
 * @package    local_integrationtests
 * @copyright  2026 Rex Robert Delacruz <rdelacruz@ubiquitous-tech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by Behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use local_integrationtests\api\oauth2_service;

/**
 * Behat step definitions for local integration tests.
 */
class behat_local_integrationtests extends behat_base {

    /**
     * @BeforeScenario
     */
    public function before_scenario_setup_oauth2(BeforeScenarioScope $scope): void {
        $tags = $scope->getScenario()->getTags();

        $services = [];

        foreach ($tags as $tag) {
            if (!str_starts_with($tag, 'oauth2_')) {
                continue;
            }

            $services[] = substr($tag, strlen('oauth2_'));
        }

        if (empty($services)) {
            return;
        }

        $this->reset_all_data();

        foreach ($services as $service) {
            oauth2_service::ensure_oauth2_service($service);
        }
    }

    /**
     * Reset the test database before the scenario so setup is isolated.
     */
    protected function reset_all_data(): void {
        global $DB;
        $DB->delete_records('oauth2_issuer');
        $DB->delete_records('oauth2_endpoint');
        $DB->delete_records('oauth2_user_field_mapping');
    }

    /**
     * @Given OAuth2 authentication is enabled
     */
    public function oauth2_authentication_is_enabled(): void {
        set_config('auth', 'manual,oauth2');
        set_config('registerauth', 'oauth2');
    }
}

