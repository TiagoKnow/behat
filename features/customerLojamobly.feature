# features/customer_lojamobly.feature
Feature: Customer Loja mobly
  In order to secure that
  As product manager
  I want to be sure that our customer can change your personal data

  @javascript @customer
  Scenario: customer edit
    Given I am logged with "lojamobly"
    When I go to "https://alice-secure.mobly.dev/customer/account/"
    And I click ".sel-link-edit-contact"
    And I set cookie name "showNewsLetterThisSession" with value false
    And I reload the page
    And I wait 3 seconds
    And I fill in "EditForm_last_name" with "teste"
    And I click "#send"
    And I wait 3 seconds
    Then I should see "Meus pedidos Loja Mobly"
