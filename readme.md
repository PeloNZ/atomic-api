# Atomic Client PHP Library

* [Set up guide](#set-up-guide)
  + [Front end SDK](#front-end-sdk)
    - [Authentication](#authentication)
  + [Back end API](#back-end-api)
    - [Authentication](#authentication-1)
    - [API credential roles](#api-credential-roles)

For the https://atomic.io API

## Set up guide

See here https://documentation.atomic.io/start-here/getting-up-and-running for the full guide

There are two components to set up.

### Front end SDK

The front end web SDK uses simple embedded Javascript. You can test this with the `demo-atomic.html` script.

#### Authentication
These steps describe how to get a basic demo working. Your application should deal with most of this via the Atomic API.


See https://documentation.atomic.io/install/auth-SDK
1. Generate a private/public key pair
   
        ssh-keygen -t rsa -P "" -b 4096 -m PEM -f jwtRS256.key
   
        ssh-keygen -e -m PKCS8 -f jwtRS256.key > jwtRS256.key.pub
   
2.  Add your public key in the Workbench
    * From the settings menu in the Workbench sidebar menu, choose SDK.
    * Under Keys, click the New key button in the top-right corner.
    * Create a name for your key e.g. production-site-1. Avoid using spaces.
    * Paste in your public key and click add.
    
3. Step 3 - Sign a JWT with your private key
    * Use the `jwt.js` node script or an alternative method to sign your key
    * Add variables to script consts
    * Install packages if necessary
    * run `node Examples/jwt.js`
  
4. Test the front end connection
   * Copy the JWT to the `demo-atomic.html` script
   * Set all the other variables to match the workbench configuration
   * Open `demo-atomic.html` in your browser.
   * Check the devtools network tab to see what happens
   * You should see the Atomic notification bubble on the page
   
Now you are ready to integrate the SDK in to your App front end.

* https://documentation.atomic.io/install/web

### Back end API

The Atomic API is used by your internal systems to send and receive data from Atomic. API endpoints are grouped into two primary concerns:

* Events API - create, update and remotely action cards in response to events in your system, and manage end-users.
* Workbench API - manage workbench resources, typically as part of your CD pipeline.
* Authentication API - manage credentials
* In addition to this, Webhooks allow you to receive data back from Atomic.

#### Authentication
https://documentation.atomic.io/api/auth

Requests to the Atomic API are authenticated using the client credentials oauth2 flow, via a client id and secret created in the Atomic Workbench. The basic flow is

* Base64-encode your client id and secret
* Pass this value as the Authorization header in a request to our oauth server
* Use the returned token to authenticate requests to Atomic

Atomic's oauth url is: https://master-atomic-io.auth.us-east-1.amazoncognito.com/oauth2/token

Tokens have a limited lifespan, so should be renewed when appropriate.

#### API credential roles
Each API credential pair is assigned one of the following roles

* auth - used only to create and rotate other credentials
* workbench - used to manage workbench resources such as cards and SNS platforms
* events - used to create cards, and manage end users
* From the Workbench, go to Settings > API.
* Select 'New credential', and select the role.
* Copy the API Client IDs and Secrets in to your API code.

Now you can send API requests to Atomic. See https://documentation.atomic.io/api/card-creation to get started.