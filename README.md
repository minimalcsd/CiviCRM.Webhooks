# CiviCRM.Webhooks

## Local Setup

Run `docker compose up --build -d`

Then open CiviCRM on http://localhost:8760 and login with `admin` and `password`.

Go to Administer -> System Settings -> Extensions, then find the extension "Circle Cloud Webhook" and enable it.

Finally, go to http://localhost:8760/civicrm/admin/setting/circle_cloud_webhook and set the URL in the config on that page to http://host.docker.internal:3000/civicrm/{{INTEGRATION_ID}}/contacts/webhook

## Notes

To run commands within the CiviCRM instance, run `docker compose exec app bash`
