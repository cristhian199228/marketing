export const oauthConfig = {
    auth: {
        clientId: process.env.MIX_AZURE_AD_CLIENT_ID,
        authority: `https://login.microsoftonline.com/${process.env.MIX_AZURE_AD_TENANT_ID}`,
        redirectUri: process.env.MIX_AZURE_AD_REDIRECT_URI,
        requireAuthOnInitialize: true
    },
    framework: {
        globalMixin: true
    }
};
