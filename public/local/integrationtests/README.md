# local_integrationtests

Reusable integration test fixtures for Moodle Behat tests.

## OAuth2 Support

This plugin automatically provisions OAuth2 issuers before a scenario runs.

Add an OAuth2 tag to your scenario:

```gherkin
@oauth2_google
Scenario: Login using Google
```

Multiple issuers are supported:

```gherkin
@oauth2_google @oauth2_microsoft @oauth2_linkedin
Scenario: Login as admin
```

---

## Supported Providers

### Standard Moodle Providers

Any provider that exists under:

```php
core\oauth2\service
```

Examples:

- google
- microsoft
- facebook
- linkedin
- clever
- nextcloud
- imsobv2p1

### Custom Providers

Any unknown provider is automatically created as a custom OAuth2 issuer.

```gherkin
@oauth2_moodletest
Scenario: Test custom provider
```

---

## Configuration

OAuth2 issuers can be configured using environment variables.

Format:

```text
<SERVICE>_<PROPERTY>
```

Examples:

```bash
export GOOGLE_CLIENTID=myclientid
export GOOGLE_CLIENTSECRET=mysecret

export NEXTCLOUD_BASEURL=https://nextcloud.example.com

export MOODLETEST_BASEURL=https://oauth.example.com
export MOODLETEST_CLIENTID=testclient
export MOODLETEST_CLIENTSECRET=testsecret
```
### Available Properties
 
Any valid OAuth2 issuer property can be configured through environment variables.
 
The list of supported properties is defined by:
 
```php
core\oauth2\issuer::define_properties()
```


---

## Example Usage

```bash
export GOOGLE_CLIENTID=testclient
export GOOGLE_CLIENTSECRET=testsecret
```

```gherkin
@javascript
@oauth2_google
Scenario: Login as admin
  Given I log in as "admin"
```

The plugin will:

1. Reset OAuth2 test data.
2. Create the Google issuer.
3. Apply environment overrides.
4. Enable the issuer.
5. Execute the scenario.
``

## Notes
This plugin only provisions OAuth2 issuers within Moodle. It does not automate third-party authentication flows.

Services such as Google may introduce MFA, CAPTCHA, consent screens, verification prompts, or other dynamic security checks that are outside Moodle's control and may not be suitable for reliable Behat automation.