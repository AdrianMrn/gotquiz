# gotquiz
School Project for Web Development 3 - Contest with Game of Thrones quiz

Models & Migrations: http://laravelsd.com/59d1fd2bb9cfe

API used: https://www.anapioficeandfire.com



# Deploy
General info
GoTQuiz, or Game of Thrones Quiz, is a contest where you can win prizes by completing quizzes successfully. The questions in the quizzes are generated at random from data from this public API: anapioficeandfire.com. The information from this API is refreshed each day at midnight.

You can change the amount of questions in a quiz, the amount of time allowed to complete the quiz and the amount of attempts (participations) a user gets each day. The contest runs in seasons which can be adjusted by an admin. At the end of a season, the contest admin gets an email telling him who won the season. Each day at midnight the contest admin also gets an email with an excel file with all the participations from the last 24 hours (whether it be in this season or one that has already ended). Admins can also export excel files directly through the back-end, these will contain all the participations for the chosen season.

You can get more daily chances by referring people to the website using your personal referral link (found on the homepage).

Setup
To run this application, you need a server with an internet connection, Apache, >PHP7, a MySQL database and access to cronjobs. You will also need an API key for MailGun to send the emails. The application is built using only Laravel. Below you can find a number of steps you need to go through to set it up on your machine.

1. Clone the project from https://github.com/adrianmrn/gotquiz
2. Edit the DocumentRoot setting in your Apache sites-enabled config file to point to the /public folder.
3. Copy and rename the .env.example file to .env and edit the following values: database info, APP_KEY, MAIL_DRIVER (should be = mailgun); MAILGUN_DOMAIN, MAILGUN_API (get these last two from your MailGun account).
4. Run “composer update” (or php composer.phar update if you don’t have composer in your $PATH variable) in the project root folder, and wait for composer to install all dependencies. This might take a while.
5. Run the command php artisan migrate, then php artisan db:seed, then php artisan do:getdata. You will now have all the tables in your database, a default admin account and all the data from the aforementioned API.
6. Create the following cronjob * * * * * php {projectfolder}/artisan schedule:run >> /dev/null 2>&1
7. Go to the website and create a new account that you will use as your admin account (do not use the default admin account created, this should be a backup).
8. Log in to the default admin account using these credentials: username=admin@admin.com password=lolzibar and grant your created account admin privileges by going into the dashboard (link at the bottom of the page) and promoting the account. For safety reasons, you cannot demote or delete the default admin account.
9. Log back into the created account that is now admin, go into the dashboard and create your contests. GoTQuiz is now ready to use!
