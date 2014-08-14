API Controller Bundle Documentation
======

What is OAuth
----------------

Why are we using OAuth
----------------

OAuth 2.0 Basics
----------------

OAuth is a relatively simple protocol that allows a user to grant an application
access to their data on a separate server or platform. For example allowing a
event planning application to access your Google Calendar to find your free time
automatically.

To implement OAuth 2.0 one must understand the 'players' involved in setting up
OAuth2 authorization/communication. At a minimum there are three players.

 * Server
   * Provides Auth,Token endpoints as well as an REST or other web service API.
 * Client App
   * Provides a name and redirect url
 * User - Often one @Server and one @ClientApp. For example you would have an account
   with Google for their calendar and one with the event planning application example
   above.

For Our Demo
------------

Server: http://nuvi.noblet.ca
 * Auth Endpoint: https://nuvi.noblet.ca/oauth/v2/auth
 * Token Endpoint: https://nuvi.noblet.ca/oauth/v2/token
Client: http://nuviclient.noblet.ca/
 * Redirect Url: http://nuviclient.noblet.ca/authorize
For our purposes we'll use
 * User @Server: api@noblet.ca
 * User @Client: remote@noblet.ca

Step One
--------
A **Developer @ClientApp** would fill out a registration form on the Server
application. At a minimum the developer will provide a name for this client and
a url where users who approve the access will be redirected back to **@ClientApp**.

Developer @ClientApp Registers With
-----------------------------------
 * ClientApp Name: NUVIClient
 * Redirect URL: http://nuviclient.noblet.ca/authorize

Server Provides
---------------
 * Client ID: 10_asiRasuf...
 * Client Secret: adfiaue...

These two must be stored and kept safe since gaining these two pieces of
information allows an attacker to impersonate the **ClientApp** and have access to
the User's data **@Server**.

Step Two
--------
ClientApp creates a link that sends the User can click on sending them to the
Server. This url will include the client id and secret and as such should
be accessed over a secured channel such as HTTPS/SSL. The url is the **Server's**
Auth Endpoint providing OAuth2 required parameters.

 * Client Id
 * Client Secret
 * The redirect URL - If this doesn't match what was provided to the Server during
   ClientApp registration authentication will fail.
 * A grant type - In our case we are requesting the Authorization Code flow

Step Three
----------
Given the above, if I were a ClientApp developer I would present the User with a
link whose target was (line breaks have been added for readability):

```
https://nuvi.noblet.ca/oauth/v2/auth?
    client_id=10_asiRsasu&
    client_secret=adfiaue&
    redirect_uri=http://nuviclient.noblet.ca/authorize&
    grant_type=code
```

Obviously clicking on this link will send the User from **ClientApp** to the page
on the **Server**. It is the responsibility of the server to authenticate the user
against its own user repository. Once logged into the server the User will be
presented with a 'grant authorization' page. This typically displays the name the
**ClientApp** registered with and a list of requested permissions. In our case there
are no specific permissions. However in the calendar example we mentioned previously
one could imagine it would ask for read only access to all events or perhaps only
access to the 'free/busy' information. To complete the connection the user must
accept the request.

Step Four
----------

Upon accepting the permissions the **Server** will redirect the User back to
**ClientApp** using the redirect url previously registered and requested in the
incoming request from **ClientApp**. The server adds a query parameter to the
redirect.

So for our example the server would cause a redirect to the url
http://nuviclient.noblet.ca/authorize?code=12SFg15d662FFSGha.


Step Five
---------
The **ClientApp** page handling this request has a few things to do in a small
window of time. It must use the code which has a validity of only a few minutes
to request an access token from the server's token endpoint. This is typically a
POST request.

For example the **ClientApp** would make a background request to http://nuvi.noblet.ca/oauth/v2/token
including Client ID, Client Secret, the Code and Grant type. In our case this
would look like:

```
POST nuvi.noblet.ca/oauth/v2/token
Accept: application/json

client_id=10_asiRsasu&
client_secret=adfiaue&
code=12SFg15d662FFSGha&
grant_type=authorization_code
```

The response of which would be a JSON encoded array that provides the access token,
expiry in seconds and if granted by the server a refresh_token. It would look something
like:

```
{ "access_token": "NjlmNDNiZT....", "expires_in": 3600, "refresh_token": "ZGU2NzlhOT...." }
```

As you can see the access token is only good for one hour. The expiry depends on
the particular server's configuration. The refresh token has a longer expiry
however it only valid in exchange for a new access token. It will not grant you
access to the API functions.

**ClientApp** would typically store the access and refresh token and expiry to be
able for the duration that it requires to communicate with the **Server**. In our
setup the refresh tokens are good for 2 weeks.

Step Six
--------

**ClientApp** should implement a way to detect expired tokens using the refresh
token. If the access token is expired
a special request to the token endpoint. For example:

```
POST nuvi.noblet.ca/oauth/v2/token
Accept: application/json

client_id=10_asiRsasu&
client_secret=adfiaue&
refresh_token=ZGU2NzlhOT....&
grant_type=refresh_token
```

Results in a new access and refresh token.

```
{ "access_token": "GjRlSauxx231...", "expires_in": 3600, "refresh_token": "DAaf1as15qbe9T...." }
```

If the refresh token has expired steps 2 to 5 must be repeated.

Step Seven
----------
**ClientApp** can now make requests of the **Server** API. When using a REST based
api, the HTTP Headers and Verbs (GET,POST,PUT,PATCH,DELETE) and HTTP Return codes
(200 OK, 201 Created, 401 Bad Request, 404 Not Found etc) are much more important
than in a simple html browser based system.

For example to make a simple GET request that returns data, one must specify the
desired return format using the 'Accept' HTTP header. Access to the API is protected
by the access token which is passed via the 'Authorization' HTTP header.

An example request to a test function would look as follows:

```
GET nuvi.noblet.ca/api/v1/test
Accept: application/json
Authorization: Bearer XXXXXX
```

The response would be:

```
200 OK
Content-Type: application/json
Content-Length: 63

{ "username": "api@noblet.ca",
"roles": ["ROLE_COUNTRY_API" ] }
```

A successful call to the API's create case would create the following request:

```
POST nuvi.noblet.ca/api/v1/ibd/cases
Content-Type: application/json
Accept: application/json
Authorization: Bearer GjRlSauxx231

{"caseId": "ACaseId", "type": 1, "site": 394 }
```

And receive a response with an empty content body such as:

```
201 Created
Location: http://nuvi.noblet.ca/api/v1/ibd/cases/CA-ALBCHILD-14-000001
```

From here on **ClientApp** developers should use the API documentation to interact
with the **Server**.


Additional Resources
--------------------

 * [https://developers.google.com/accounts/docs/OAuth2WebServer]
   Very good OAuth2 documentation for Google's implementation of the protocol and
   similar to how our implementation
 * [http://blog.tankist.de/blog/2013/07/16/oauth2-explained-part-1-principles-and-terminology/]
   PHP/Symfony based examples of implementing OAuth on a server. However includes
   many command line examples of working with REST APIs. It also includes documentation
   on other 'Grant Types' we haven't included here.
 * [http://aaronparecki.com/articles/2012/07/29/1/oauth2-simplified#other-app-types]
 * [http://oauth.net/2/] - Official OAuth 2.0 specification. Good but highly technical.
 * [https://github.com/adoy/PHP-OAuth2] - A very simple PHP based OAuth2 client.
 * Chrome has a browser extension called 'POSTMAN' that allows one to manually
   craft requests to help debug and develop an OAuth client.
