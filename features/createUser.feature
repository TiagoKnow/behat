# features/createUser.feature
Feature: Signup
  In order to secure that new customers can registrate (PF, PJ and lojamobly (PJ only))
  As product manager
  I want to be sure that our create account pages are working correctly

  @javascript @creation
  Scenario Outline: account creation
    Given I am signup <siteVersion> as <userType>
    Then I should be on <url>

    Examples:
      |siteVersion|userType|url|
      |"lojamobly"|"PJ"|"https://alice-secure.mobly.dev/customer/account/"|
      |"mobly"|"PF"|"/"|
      |"mobly"|"PJ"|"/"|

