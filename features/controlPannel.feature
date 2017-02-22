# features/controlPannel.feature
@javascript @controlpannel @ci
Feature: login
  As product manager
  I want to be sure that:
  * Loja Mobly logged users should see lojamobly and mobly own orders
  * Mobly logged users should only see mobly own orders

  Scenario: loja mobly logged user accessing control pannel
    Given I am logged with "lojamobly"
    When I go to "https://alice-secure.mobly.dev/customer/account/"
    Then I should see "Meus pedidos Loja Mobly"
    And I should not see "Meus endereços"


  Scenario: mobly logged user accessing control pannel
    Given I am logged with "mobly"
    When I go to "https://alice-secure.mobly.dev/customer/account/"
    Then I should not see "Meus pedidos Loja Mobly"
    And I should see "Meus endereços"