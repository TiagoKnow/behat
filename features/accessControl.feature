# features/accessControl.feature
@access @ci
Feature: login
  In order to secure access rules previously defined
  As product manager
  I want to be sure that:
  * Guests will only have access to create account and login page
  * Mobly users should be redirect to home
  * Loja Mobly users should have full access


  Scenario Outline: guest accessing loja mobly pages
    Given I am logged with <typeUser>
    When I go to <urlVisited>
    Then I should be on <actualPage>

    Examples:
      |typeUser|urlVisited|actualPage|
      |"guest"|"/lojamobly/"|"/lojamobly/account/login"|
      |"guest"|"/lojamobly/all-products"|"/lojamobly/account/login"|
      |"guest"|"/lojamobly/account/create"|"/lojamobly/account/create"|
      |"guest"|"/lojamobly/moveis/moveis-sala/"|"/lojamobly/account/login"|
      |"guest"|"/lojamobly/mobly/"|"/lojamobly/account/login"|

  @javascript
  Scenario Outline: mobly user accessing loja mobly pages
    Given I am logged with <typeUser>
    When I go to <urlVisited>
    Then I should be on <actualPage>

    Examples:
      |typeUser|urlVisited|actualPage|
      |"mobly"|"/lojamobly/"|"/"|
      |"mobly"|"/lojamobly/all-products"|"/"|
      |"mobly"|"/lojamobly/account/create"|"/"|
      |"mobly"|"/lojamobly/moveis/moveis-sala/"|"/"|
      |"mobly"|"/lojamobly/mobly/"|"/"|

  @javascript @lm
  Scenario Outline: logged accessing loja mobly pages
    Given I am logged with <typeUser>
    When I go to <urlVisited>
    Then I should be on <actualPage>

    Examples:
      |typeUser|urlVisited|actualPage|
      |"lojamobly"|"/lojamobly/"|"/lojamobly/"|
      |"lojamobly"|"/lojamobly/all-products"|"/lojamobly/all-products"|
      |"lojamobly"|"/lojamobly/account/create"|"/lojamobly"|
      |"lojamobly"|"/lojamobly/moveis/moveis-sala/"|"/lojamobly/moveis/moveis-sala/"|
      |"lojamobly"|"/lojamobly/mobly/"|"/lojamobly/mobly/"|

