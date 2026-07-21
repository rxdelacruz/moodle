@core @core_admin @core_admin_roles
Feature: Create, export and import roles
  In order to be able to reuse custom roles
  As an admin
  I need to be able to export and import them

  @javascript
  Scenario: Create and export a role
    Given I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    When I press "Add a new role"
    And I press "Continue"
    And I set the following fields to these values:
      | Short name       | customrole  |
      | Custom full name | Custom role |
      | System           | 1           |
    And I press "Show advanced"
    And I click on "Allow" "radio" in the "Add a new admin bookmarks block to Dashboard" "table_row"
    And I click on "Prohibit" "radio" in the "Add a new Latest badges block to Dashboard" "table_row"
    And I click on "Prevent" "radio" in the "Add a new calendar block to Dashboard" "table_row"
    And I press "Create this role"
    And I should see "Custom role"
    And I should see "Allow" in the "Add a new admin bookmarks block to Dashboard" "table_row"
    And I should see "Prohibit" in the "Add a new Latest badges block to Dashboard" "table_row"
    And I should see "Prevent" in the "Add a new calendar block to Dashboard" "table_row"
    # Then following "Export" button should download a file that:
    #   | Has mimetype                 | text/xml                                              |
    #   | Contains text in xml element | customrole                                            |
    #   | Contains text in xml element | <allow>block/admin_bookmarks:myaddinstance</allow>    |
    #   | Contains text in xml element | <prevent>block/calendar_month:myaddinstance</prevent> |
    #   | Contains text in xml element | <prohibit>block/badges:myaddinstance</prohibit>       |

  @javascript @_file_upload
  Scenario: Import a role
    Given I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    When I press "Add a new role"
    And I upload "admin/roles/tests/fixtures/rolepreset.xml" file to "Use role preset" filemanager
    And I press "Continue"
    And I press "Create this role"
    And I should see "Allow" in the "Add a new admin bookmarks block to Dashboard" "table_row"
    And I should see "Prohibit" in the "Add a new Latest badges block to Dashboard" "table_row"
    And I should see "Prevent" in the "Add a new calendar block to Dashboard" "table_row"