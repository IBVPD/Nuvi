Feature: All Case forms are viewable

@mink:symfony2
Scenario Outline: All IBD forms
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I go to "<path>"
    Then There should be no exception
      And I should be on "<path>"
    Examples:
      | email             | password            | path     |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/outcome/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/lab/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/rrl/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/nl/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/outcome/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/lab/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/rrl/edit/CA-ALBCHLD-14-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/nl/edit/CA-ALBCHLD-14-000001 |
