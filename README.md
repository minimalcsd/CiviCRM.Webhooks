# CiviCRM.Webhooks

## Local Setup

Run `docker compose up --build -d`

Then open CiviCRM on http://localhost:8760 and login with `admin` and `password`.

Go to Administer -> System Settings -> Extensions, then find the extension civiwebhook and enable it.

Finally go to http://localhost:8760/civicrm/admin/setting/civiwebhook?reset=1 and set the URL in the config on that page to http://host.docker.internal:3000/civicrm/{{INTEGRATION_ID}}/contacts/webhook
