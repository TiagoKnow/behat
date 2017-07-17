# features/purchaising.feature
@purchaise @javascript @ci
Feature: purchaising
  In order to secure that all things are fine
  As product manager
  I want to be sure that:
  * Customers are able to add products in cart
    go to checkout and close the deal

  Scenario: Purchasing test
    Given I am logged with "mobly"
    And I go to "https://rocket:rock4me@cart-stg02.mobly.com.br/cart/"
    And I wait 5 seconds
    And I go to the homepage
    When I go to "/all-products"
    And I choose the first product in catalog
    And I wait 5 seconds
    And I press "Adicionar ao carrinho"
    And I wait 5 seconds
    And I go to "https://rocket:rock4me@cart-stg02.mobly.com.br/cart/"
    And I wait 5 seconds
    And I click ".sel-cart-checkout-button"
    And I wait 5 seconds
    When I choose the radio button "#shopline"
    And I wait 5 seconds
    And I fill delivery address info if needed
    And I wait 5 seconds
    And I click "#checkoutBtn"
    And I wait 5 seconds
    Then I should see "Pagamento do pedido - Itaú Shopline"
