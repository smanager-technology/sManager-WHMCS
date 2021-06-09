# sManager Payment - WHMCS
Our goal is to reach the doorsteps of every merchant with the help of information and applications provided by sMANAGER, an application with various information related to the business's daily sales, bookkeeping, buying goods, keeping personal accounts very easily and accurately.
We are providing sManager Online Payment for WHMCS.

<h3>Prerequisites</h3>
1. WHMCS V8
2. cURL php extension.
3. [sManager Subscription](https://play.google.com/store/apps/details?id=xyz.sheba.managerapp)

<h3>How to install the WHMCS module?</h3>
To install the WHMCS payment module, follow the instructions below:

1. Download the WHMCS payment module from GitHub page.
2. Unzip the module to a temporary location on your computer.
3. Copy the <code>modules</code> folder from the archive to your base <code>whmcs</code> folder (using FTP program or similar)
4. This should NOT overwrite any existing files or folders and merely supplement them with the SSLWireless files
5. Login to the WHMCS Administrator console
6. Using the main menu, navigate to <code>Setup > Payments > Payment Gateways</code>
7. Select <code>sManager Online Payment</code> from the <code>All Payment Gateways</code> drop-down list and click <code>Activate</code>
8. Enter the following details under the sManager Online Payment heading and click <code>Save Changes</code>:
   - Display Name
   - Client Id
   - Client Secret
9. The module is now and ready.

<h3>Image Reference</h3>

> Payment Methods
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/payment_methods.png" alt="Payment Methods" />

> Gateway Configuration Page
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/Gatway_Configuration_Page.png" alt="Payment Methods" />

> sManager Online Payment
> <img src="https://raw.githubusercontent.com/smanager-technology/sManager-WHMCS/master/images/sManager_online_payment.png" alt="Payment Methods" />