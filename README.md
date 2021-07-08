# sManager Payment - WHMCS
Our goal is to reach the doorsteps of every merchant with the help of information and applications provided by sMANAGER, an application with various information related to the business's daily sales, bookkeeping, buying goods, keeping personal accounts very easily and accurately.
We are providing sManager Online Payment for WHMCS.

<h3>Prerequisites</h3>

1. WHMCS V8
2. cURL php extension.
3. [sManager Subscription](https://play.google.com/store/apps/details?id=xyz.sheba.managerapp)

<h3>How to install the WHMCS module?</h3>
To install the WHMCS payment module, follow the instructions below:
<br />

1. Download the WHMCS payment module from GitHub page.
2. Unzip the module to a temporary location on your computer.
3. Copy the <code>modules</code> folder from the archive to your base <code>whmcs</code> folder (using FTP program or similar)
4. This should NOT overwrite any existing files or folders and merely supplement them with the SSLWireless files
5. Login to the WHMCS Administrator console
6. Using the Top Right Menu, click <code>System Settings</code>. Search <code>API Credentials</code> and click.
7. Click <code>API Role</code>, click create API Role. Then give API role name and description. The description is optional, select the billing option from Allowed API Actions and hit save.
8. Click <code>API Credentials</code>, click <code>Generate New API Credentials</code>, select Admin User and API Role(s), hit <code>Generate</code>.
9. Open sManagermodule.php <code>(modules/gateways/callback/sManagermodule.php)</code> with file editor.
10. Change the value of <code>$username</code> with <code>Identifier</code> generated in API Credentials.
11. Change the value of <code>$password</code> with <code>Secret</code> generated in API Credentials.
12. Save the <code>sManagermodule.php</code> file.
13. Using the Top Right Menu, navigate to <code>System Settings > Payment Gateways</code>
14. Navigate to <code>Manage Existing Gateways</code>
15. Enter the following details under the sManager Online Payment heading and click <code>Save Changes</code>:
   - Display Name
   - Client Id
   - Client Secret
   - Additional Service Charge (%) (if needed)
   - Additional Service Charge (৳) (If Needed)
   - Convert To For Processing ()

16. The module is now ready to use.

<h3>Image Reference</h3>

> System Settings
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/WHMCS-System-Settings.png" alt="WHMCS - System Settings" />

> API Credentials
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/API_Credentials.PNG" alt="API Credentials" />

> Generate New Api Credential
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/generate_new_api_credentials.PNG" alt="Generate New Api Credential" />

> Payment Gateways
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/payment_methods.png" alt="Payment Methods" />

> Gateway Configuration Page
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/Gateway_Configuration_Page.png" alt="Payment Methods" />

> sManager Online Payment
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/sManager_online_payment.png" alt="Payment Methods" />