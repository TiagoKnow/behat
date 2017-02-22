# features/login.feature
Feature: login
  In order to secure a login funcionality
  As product manager
  I want to be sure that both mobly and loja mobly logins are functional
  and redirect the logged user to the right page

  @javascript @login
  Scenario Outline: login mobly loja mobly from login page
    Given I am logged with <typeUser>
    Then I should be on <url>

    Examples:
      |typeUser|url|
      |"lojamobly"|"/lojamobly/"|
      |"mobly"|"/customer/account/"|



