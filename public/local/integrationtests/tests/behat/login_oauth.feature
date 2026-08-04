@local @local_integrationtests
Feature: Login using OAuth2

  Background:
    Given I log in as "admin"
    And OAuth2 authentication is enabled
    And I log out

  @javascript @oauth2_google
  Scenario: Login using Google Oauth2
    Given I click on "Log in with Google" "link"
    And I set the field "identifier" to "testoauth.moodle@test.com"
    And I press "Next"
    And I set the field "Passwd" to "password"
    And I press "Next"
    # After entering the email, Google may display different screens depending on
    # the account state, browser, or additional security requirements (e.g. MFA,
    # CAPTCHA, consent, or account verification prompts).

  @javascript @oauth2_microsoft
  Scenario: Login using Microsoft Oauth2
    Given I click on "Log in with Microsoft" "link"
    And I set the field "identifier" to "testoauth.moodle@test.com"
    And I press "Next"
    And I set the field "Passwd" to "password"
    And I press "Next"