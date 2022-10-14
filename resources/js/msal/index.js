import * as msal from "@azure/msal-browser";
import store from '../store'

const msalConfig = {
    auth: {
        clientId: process.env.MIX_AZURE_AD_CLIENT_ID,
        authority: `https://login.microsoftonline.com/${process.env.MIX_AZURE_AD_TENANT_ID}`,
        redirectUri: process.env.MIX_AZURE_AD_REDIRECT_URI
    }
};

let accessToken;
let username = "";

const msalInstance = new msal.PublicClientApplication(msalConfig);

const loginRequest = {
    scopes: ["user.read"]
};

msalInstance
    .handleRedirectPromise()
    .then(handleResponse)
    .catch(err => {
        console.error(err);
    });

function handleResponse(resp) {
    if (resp !== null) {
        username = resp.account.username;
        store.dispatch('currentUser/login', resp.accessToken)
    } else {
        const currentAccounts = msalInstance.getAllAccounts();
        if (currentAccounts === null) {
            return;
        } else if (currentAccounts.length > 1) {
            // Add choose account code here
            console.warn("Multiple accounts detected.");
        } else if (currentAccounts.length === 1) {
            username = currentAccounts[0].username;
        }
    }
}

export function signIn() {
    msalInstance.loginRedirect(loginRequest);
}

export function signOut() {
    const logoutRequest = {
        account: msalInstance.getAccountByUsername(username)
    };

    return msalInstance.logoutRedirect(logoutRequest);
}

function getTokenRedirect(request) {
    request.account = msalInstance.getAccountByUsername(username);
    return msalInstance.acquireTokenSilent(request).catch(error => {
        console.warn(
            "silent token acquisition fails. acquiring token using redirect"
        );
        if (error instanceof msal.InteractionRequiredAuthError) {
            // fallback to interaction when silent call fails
            return msalInstance.acquireTokenRedirect(request);
        } else {
            console.warn(error);
        }
    });
}
