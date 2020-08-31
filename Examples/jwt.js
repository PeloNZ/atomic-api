const fs = require("fs");
const jwt = require("jsonwebtoken");
const path = require("path");

// You should have generated these keys in a previous step.
const PRIVATE_KEY_PATH = path.join(__dirname, 'private-key-file-name');
const privateKey = fs.readFileSync(PRIVATE_KEY_PATH, "utf8");
// The API key you were provided by the Atomic Workbench.
const apiKey = "your-api-key"
// Sub is a standard field of JWT, and must be a unique identifier for the user in your system.
// Private identifiers of users such as database ids do not need to be used.
// The only requirement is that the sub is unique and always the same for any single user.
const subId = "the.user.id";
// Optional, add to Allowed Issuers in the Workbench.
const issuer = "your.site.url";
// You must assign an expiry to your token, no longer than 7 days.
const expiry = '7d';
// You may only use RS256 for signing the token.
const algo = 'RS256';

const token = jwt.sign({
    "iss": issuer,
    "aud": "https://atomic.io",
    "apiKey": apiKey,
    "sub": subId
}, privateKey, {
    expiresIn: expiry,
    algorithm: algo
});

console.log(token);
