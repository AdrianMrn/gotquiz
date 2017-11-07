<?php
    while(true)
    {
        sleep(60); // sleep for x sec
        echo exec('php /home/ubuntu/workspace/artisan schedule:run >> /tmp/crontab.log 2>&1');
    }

//run this file by running "nohup php cron.php"