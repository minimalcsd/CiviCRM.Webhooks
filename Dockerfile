FROM civicrm/civicrm:latest

RUN apt-get update && apt-get install -y netcat-openbsd curl \
 && curl -LsS https://download.civicrm.org/civix/civix.phar -o /usr/local/bin/civix \
 && chmod +x /usr/local/bin/civix

