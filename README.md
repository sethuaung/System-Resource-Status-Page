# System-Resource-Status-Page
PHP dashboard for real-time system resource monitoring, hardware information, open TCP/UDP ports, web service ports (HTTP/HTTPS), and installed packages, along with HSTS and self-signed HTTPS support for local deployment.

### Automating Data Storage with Cron Jobs

Schedule the script (`system-monitor.php`) to run every hour using a cron job:

1.  Edit the crontab:

    bash

    ```
    crontab -e

    ```

2.  Add this line to schedule the script every hour:

    bash

    ```
    0 * * * * /usr/bin/php /path/to/system-monitor.php

    ```


### Summary of Features:

1.  **Real-Time Dashboard:** Displays live system metrics using AJAX and Chart.js..

2.  **MongoDB Integration:** Stores system monitoring data in an online MongoDB database (`metrics` collection) every hour.

3.  **Secure Connection:** HSTS and HTTPS enabled for local deployment.

4.  **Automation:** Data is logged automatically via a cron job.

5.  **Extensible Design:** Easily customizable to add more features in the future.

### Licnese:
GNU GENERAL PUBLIC LICENSE
