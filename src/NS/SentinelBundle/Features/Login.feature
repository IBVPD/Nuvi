Feature: User Views
  Login as User >> validate only cases from assigned site are seen	
  Login as User >> validate that only able to enter data based on assigned access rights	
  Login as Country >> validate only cases from assigned country are seen	
  Login as Country >> validate only able to enter data based on assigned access rights	
  Login as RO >> validate only able to enter data based on assigned access rights	
  Login as RO >> validate only cases from assigned countries/sites are seen	
  Ability to create a new IBD CASE entry	
  Ability to a new IBD LAB entry to existing CASE entry	
  Ability to create a New IBD Lab entry WITHOUT existing CASE entry	
  Login as RRL >> validate only cases from assigned countries are seen	
  Ability to populate all variables in the IBD Case entry form	
  Ability to create a new Rotavirus CASE entry	
  Ability to create a new RRL entry (try as both regular user and as RRL user)	
  Ability to create a new NL entry (try as both regular user and as NL user)

Scenario: A user cannot find cases outside their rights
    Given I am not logged in
      And I login with "site-alberta@noblet.ca" "1234567-alberta"
      And I should be on "/en"
      And I go to "/en/ibd"
      And I fill in "ibd_filter_form[id]" with "MX"
      And I press "ibd_filter_form_find"
    Then I should be on "/en/ibd/"
      And I should see 0 "#ibdCases tbody tr" elements

Scenario: A user cannot directly access a case outside their rights
    Given I am not logged in
      And I login with "site-alberta@noblet.ca" "1234567-alberta"
      And I go to "/en/ibd/edit/MX-MGH-14-000001"
    Then I should see "This case does not exist!"

Scenario Outline:
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I should be on "/en"
      And I go to "/en/ibd"
    Then I should see <numCases> "#ibdCases tbody tr" elements
      And I am not logged in
    Examples:
      | email                  | password        | numCases |
      | site-alberta@noblet.ca | 1234567-alberta | 3        |
      | site-seattle@noblet.ca | 1234567-seattle | 5        |
      | site-toronto@noblet.ca | 1234567-toronto | 7        |
      | site-mexico@noblet.ca  | 1234567-mexico  | 8        |

Scenario Outline:
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I should be on "/en"
      And I go to "/en/rota"
    Then I should see <numCases> "#rotaCases tbody tr" elements
      And I am not logged in
    Examples:
      | email                  | password        | numCases |
      | site-alberta@noblet.ca | 1234567-alberta | 8        |
      | site-seattle@noblet.ca | 1234567-seattle | 7        |
      | site-toronto@noblet.ca | 1234567-toronto | 4        |
      | site-mexico@noblet.ca  | 1234567-mexico  | 2        |
