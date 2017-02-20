# features/login.feature
Feature: login
  In order to secure a login funcionality
  As product manager
  I want to be sure that both mobly and loja mobly logins are functional
  and redirect the logged user to the right page

  @javascript
  Scenario Outline: login mobly loja mobly from login page
    Given I am logged with <typeUser>
    Then I should be on <url>

    Examples:
      |typeUser|url|
      |"lojamobly"|"http://alice.mobly.dev/lojamobly/"|
      |"mobly"|"http://alice.mobly.dev/customer/account/"|


  @javascript @box
  Scenario: login mobly from login box
    Given I am on the homepage
    And I set cookie name "showNewsLetterThisSession" with value false
    And I reload the page
    And I hover ".my-account"
    And I switch to iframe "iframeLoginBox"
    And I fill in "dacolera360@gmail.com" for "LoginForm_email"
    And I fill in "1q2w3e" for "LoginForm_password"
    When I press "Entrar"
    And I wait 5 seconds
    Then should see "Painel de Controle"

