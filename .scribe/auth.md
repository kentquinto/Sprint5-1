# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_AUTH_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Obtain a token by calling **POST /api/register** or **POST /api/login**. The token is returned in the `token` field of the response.
