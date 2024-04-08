@managing_amazon_fba_configurations
Feature: Adding a new Amazon FBA configuration
    In order to show different configurations
    As an Administrator
    I want to add a new Amazon FBA configuration to the admin

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Adding a new Amazon FBA configuration
        Given I want to add a new Amazon FBA configuration
        When I fill the code with "default"
        And I fill the client id with "123456"
        And I fill the client secret with "123456"
        And I fill the refresh token with "123456"
        And I add it
        Then I should be notified that it has been successfully created
        And the Amazon FBA configuration "default" should appear in the admin

    @ui
    Scenario: Trying to add a new Amazon FBA configuration with empty fields
        Given I want to add a new Amazon FBA configuration
        When I fill the code with "default"
        And I add it
        Then I should be notified that the form contains invalid fields

    @ui
    Scenario: Trying to add an Amazon FBA configuration with existing code
        Given there is an existing Amazon FBA configuration with "default" code
        And I want to add a new Amazon FBA configuration
        When I fill the code with "default"
        And I add it
        Then I should be notified that there is already an existing Amazon FBA configuration with provided code
