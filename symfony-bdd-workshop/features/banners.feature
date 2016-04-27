@web @javascript
Feature: Advertisement banners on homepage
    In order to earn coin
    As website owner
    I should be able to display banners on homepage

    Scenario: Show all banners
        Given banner "left_banner" exists
        And banner "right_banner" exists
        And banner "center_banner" exists
        When I go to homepage
        Then I should see "left_banner"
        And I should see "right_banner"
        And I should see "center_banner"

    Scenario: Disable one banner
        Given banner "left_banner" exists
        And banner "right_banner" exists
        And banner "center_banner" exists
        And banner "left_banner" is disabled
        When go to homepage
        Then print last response
        And print current URL
        And I should not see "left_banner"
        And I should see "right_banner"
        And I should see "center_banner"
