# features/login.feature
Feature: login
  In order to secure a login funcionality
  As ecommerce client
  I need to be able to signin using my user/(cpf|cnpj) and my password

  Scenario: login mobly
    Given I am on "https://secure.mobly.com.br/customer/account/login/"
    And I fill in "dacolera360@gmail.com" for "LoginForm[email]"
    And I fill in "1q2w3e" for "LoginForm[password]"
    When I press "Entrar"
    Then should see "Painel de Controle"

  @javascript
  Scenario: login mobly
    Given I am on the homepage
    And I hover ".my-account"
    And I switch to iframe "iframeLoginBox"
    And I fill in "dacolera360@gmail.com" for "LoginForm_email"
    And I fill in "1q2w3e" for "LoginForm_password"
    When I press "Entrar"
    And I wait 5 seconds
    Then should see "Painel de Controle"