### User Management
This repository contains REST API-driven user management Symfony app.

##### Instructions
1. Install dependencies with `composer install` and `yarn install`.
2. Build assets, `yarn encore dev`.
3. Setup database:
    * `bin/console d:d:c` 
    * `bin/console d:m:m -n` 
    * `bin/console d:f:l -n`
4. Start local server `bin/console server:start`.
5. Log in as an admin with following credentials:
    * username: `admin`
    * password: `password`

##### REST API
Endpoints: 
* `/rest/v1/teams` - create new team (POST)
* `/rest/v1/teams` - fetch all teams (GET)
* `/rest/v1/teams/{id}` - delete specific team (DELETE)
* `/rest/v1/teams/{id}` - fetch specific team (GET)
* `/rest/v1/teams/{teamId}/users/{userId}` - remove specific user from specific team (DELETE)
* `/rest/v1/teams/{teamId}/users/{userId}` - add specific user to specific team (POST)
* `/rest/v1/users` - fetch all users (GET)
* `/rest/v1/users` - create new user (POST)
* `/rest/v1/users/{id}` - fetch specific user (GET)
* `/rest/v1/users/{id}` - delete specific user (DELETE)
* `/rest/v1/users/{id}/teams` - fetch all teams a user belongs to (GET)
