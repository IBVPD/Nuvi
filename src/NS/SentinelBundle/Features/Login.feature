Feature: User Views
  Login as User >> validate that only able to enter data based on assigned access rights	
  Login as Country >> validate only able to enter data based on assigned access rights	
  Login as RO >> validate only cases from assigned countries/sites are seen	
  Ability to a new IBD LAB entry to existing CASE entry	
  Ability to create a New IBD Lab entry WITHOUT existing CASE entry	
  Login as RRL >> validate only cases from assigned countries are seen	
  Ability to populate all variables in the IBD Case entry form	
  Ability to create a new RRL entry (try as both regular user and as RRL user)	
  Ability to create a new NL entry (try as both regular user and as NL user)

@mink:symfony2
Scenario Outline: A User with create rights can't create any kind of labs
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I go to "<path>"
      And I should be on "<path>"
    Then There should be no exception
      And The Create Form Has <types> Types
      And The Create Form Has <sites> Sites
    Examples:
      | email                  | password            | types | sites    | path     |
      | rrl-alberta@noblet.ca  | 1234567-alberta-rrl | 1     | 0        | /en/ibd/ |
      | lab-alberta@noblet.ca  | 1234567-alberta-lab | 1     | 0        | /en/ibd/ |
      | nl-alberta@noblet.ca   | 1234567-alberta-nl  | 1     | 0        | /en/ibd/ |
      | ca-create@noblet.ca    | 1234567-ca-create   | 1     | 3        | /en/ibd/ |
      | site-seattle@noblet.ca | 1234567-seattle     | 1     | 0        | /en/ibd/ |
      | site-alberta@noblet.ca | 1234567-alberta     | 1     | 0        | /en/ibd/ |
      | site-shriner@noblet.ca | 1234567-shriner     | 2     | 0        | /en/ibd/ |
      | ca-crrl@noblet.ca      | 1234567-ca-crrl     | 2     | 3        | /en/ibd/ |
      | ca-clab@noblet.ca      | 1234567-ca-clab     | 2     | 3        | /en/ibd/ |
      | ca-cnl@noblet.ca       | 1234567-ca-cnl      | 2     | 3        | /en/ibd/ |

@mink:symfony2
Scenario Outline: A user cannot find cases outside their rights
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I should be on "/en"
      And I go to "<path>"
      And I fill in "<form-id>" with "<search>"
      And I press "<form-button>"
    Then There should be no exception
      And I should be on "<path>"
      And I should see 0 "<css>" elements
    Examples:
      | email                  | password        | form-id                  | form-button                | search | path       | css                  |
      | site-alberta@noblet.ca | 1234567-alberta | rotavirus_filter_form_id | rotavirus_filter_form_find | MX     | /en/rota/  | #rotaCases tbody tr  |
      | us@noblet.ca           | 1234567-us      | rotavirus_filter_form_id | rotavirus_filter_form_find | MX     | /en/rota/  | #rotaCases tbody tr  |
      | ca@noblet.ca           | 1234567-ca      | rotavirus_filter_form_id | rotavirus_filter_form_find | MX     | /en/rota/  | #rotaCases tbody tr  |
      | site-alberta@noblet.ca | 1234567-alberta | ibd_filter_form_id       | ibd_filter_form_find       | MX     | /en/ibd/   | #ibdCases tbody tr   |
      | us@noblet.ca           | 1234567-us      | ibd_filter_form_id       | ibd_filter_form_find       | MX     | /en/ibd/   | #ibdCases tbody tr   |
      | ca@noblet.ca           | 1234567-ca      | ibd_filter_form_id       | ibd_filter_form_find       | MX     | /en/ibd/   | #ibdCases tbody tr   |

@mink:symfony2
Scenario Outline: A user cannot directly access a case outside their rights
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I go to "<path>"
    Then There should be no exception
      And I should see "This case does not exist!"
    Examples:
      | email                  | password        | path                           |
      | site-alberta@noblet.ca | 1234567-alberta | /en/ibd/show/MX-MGH-14-000001  |
      | us@noblet.ca           | 1234567-us      | /en/ibd/show/MX-MGH-14-000001  |
      | ca@noblet.ca           | 1234567-ca      | /en/ibd/show/MX-MGH-14-000001  |
      | site-alberta@noblet.ca | 1234567-alberta | /en/rota/show/MX-MGH-14-000001 |
      | us@noblet.ca           | 1234567-us      | /en/rota/show/MX-MGH-14-000001 |
      | ca@noblet.ca           | 1234567-ca      | /en/rota/show/MX-MGH-14-000001 |

@mink:symfony2
Scenario Outline:  Login validate only cases from assigned site are seen	
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I should be on "/en"
      And I go to "<path>"
    Then There should be no exception
      And I should see <numCases> "<css>" elements
      And I am not logged in
    Examples:
      | email                  | password        | numCases | path      | css                 |
      | site-alberta@noblet.ca | 1234567-alberta | 3        | /en/ibd   | #ibdCases tbody tr  |
      | site-seattle@noblet.ca | 1234567-seattle | 5        | /en/ibd   | #ibdCases tbody tr  |
      | site-toronto@noblet.ca | 1234567-toronto | 7        | /en/ibd   | #ibdCases tbody tr  |
      | site-mexico@noblet.ca  | 1234567-mexico  | 8        | /en/ibd   | #ibdCases tbody tr  |
      | us@noblet.ca           | 1234567-us      | 5        | /en/ibd   | #ibdCases tbody tr  |
      | na@noblet.ca           | 1234567-na      | 10       | /en/ibd   | #ibdCases tbody tr  |
      | site-alberta@noblet.ca | 1234567-alberta | 8        | /en/rota  | #rotaCases tbody tr |
      | site-seattle@noblet.ca | 1234567-seattle | 7        | /en/rota  | #rotaCases tbody tr |
      | site-toronto@noblet.ca | 1234567-toronto | 4        | /en/rota  | #rotaCases tbody tr |
      | site-mexico@noblet.ca  | 1234567-mexico  | 2        | /en/rota  | #rotaCases tbody tr |
      | us@noblet.ca           | 1234567-us      | 7        | /en/rota  | #rotaCases tbody tr |
      | na@noblet.ca           | 1234567-na      | 10       | /en/rota  | #rotaCases tbody tr |

@mink:symfony2
Scenario Outline: A User can create a case
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I go to "<path>"
      And I should be on "<path>"
      And I fill in "<form-case>" with "<form-case-value>"
      And I fill in "<form-type>" with "<form-type-value>"
      And I press "<submit>"
    Then There should be no exception
      And I should not be on "<resultpath>"
    Examples:
      | email                  | password            | path      | form-case               | form-case-value | form-type             | form-type-value | resultpath       | submit                  |
      | site-seattle@noblet.ca | 1234567-seattle     | /en/ibd/  | create_ibd_caseId       | seattle-case-1  | create_ibd_type       | 1               | /en/ibd/create   | ibd_create_submit       |
      | site-alberta@noblet.ca | 1234567-alberta     | /en/ibd/  | create_ibd_caseId       | alberta-case-1  | create_ibd_type       | 1               | /en/ibd/create   | ibd_create_submit       |
      | site-shriner@noblet.ca | 1234567-shriner     | /en/ibd/  | create_ibd_caseId       | shriner-case-1  | create_ibd_type       | 1               | /en/ibd/create   | ibd_create_submit       |
      | site-seattle@noblet.ca | 1234567-seattle     | /en/rota/ | create_rotavirus_caseId | seattle-case-1  | create_rotavirus_type | 1               | /en/rota/create  | rotavirus_create_submit |
      | site-alberta@noblet.ca | 1234567-alberta     | /en/rota/ | create_rotavirus_caseId | alberta-case-1  | create_rotavirus_type | 1               | /en/rota/create  | rotavirus_create_submit |
      | site-shriner@noblet.ca | 1234567-shriner     | /en/rota/ | create_rotavirus_caseId | shriner-case-1  | create_rotavirus_type | 1               | /en/rota/create  | rotavirus_create_submit |
