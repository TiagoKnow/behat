# features/accessControl.feature
Feature: login
  In order to secure access rules previously defined
  As product manager
  I want to be sure that guests will only have access to create account and login page

  @javascript
  Scenario Outline: guest accessing loja mobly pages
    Given I am logged with <typeUser>
    When I go to <urlVisited>
    Then I should be on <actualPage>

    Examples:
      |typeUser|urlVisited|actualPage|
      |"guest"|"http://alice.mobly.dev/lojamobly/"|"http://alice.mobly.dev/lojamobly/account/login"|
      |"guest"|"http://alice.mobly.dev/lojamobly/all-products"|"http://alice.mobly.dev/lojamobly/account/login"|
      |"guest"|"http://alice.mobly.dev/lojamobly/account/create"|"http://alice.mobly.dev/lojamobly/account/create"|


  @javascript
  Scenario Outline: logged accessing loja mobly pages
    Given I am logged with <typeUser>
    When I go to <urlVisited>
    Then I should be on <actualPage>

    Examples:
      |typeUser|urlVisited|actualPage|
      |"lojamobly"|"http://alice.mobly.dev/lojamobly/"|"http://alice.mobly.dev/lojamobly/"|
      |"lojamobly"|"http://alice.mobly.dev/lojamobly/all-products"|"http://alice.mobly.dev/lojamobly/all-products"|
      |"lojamobly"|"http://alice.mobly.dev/lojamobly/account/create"|"http://alice.mobly.dev/lojamobly"|