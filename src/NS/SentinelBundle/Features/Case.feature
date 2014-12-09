Feature: All Case forms are viewable

@mink:symfony2
Scenario Outline: All IBD forms
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I visit "<path>" with "<id>"
    Then There should be no exception
    Examples:
      | email             | password          | path                  | id                   |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/edit/         | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/lab/edit/     | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/rrl/edit/     | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/nl/edit       | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/outcome/edit  | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/show          | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/edit/        | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/lab/edit     | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/rrl/edit     | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/nl/edit      | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/outcome/edit | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/show         | CA-ALBCHLD-%d-000001 |

@mink:symfony2
Scenario Outline: All show pages are good
    Given I am not logged in
      And I login with "<email>" "<password>"
      And I visit "<path>" with "<id>"
    Then There should be no exception
    Examples:
      | email             | password          | path                  | id                   |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/show/         | CA-ALBCHLD-%d-000001 |
      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/show/        | CA-ALBCHLD-%d-000001 |
