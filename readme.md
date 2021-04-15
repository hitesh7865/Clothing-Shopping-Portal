# YouRHired

Built with Larvel, YouRHired is a product to be used with or without Carbonate. The main aim of YouRHired is to allow companies to fetch their mailboxes and scan the emails related to Job postings and automate certain tasks such as
- Sending a thank-you email
- Asking questions when a Job Application is received
- Screening/Hiring/Rejecting the candidate right from the Application
- Integrating it with Carbonate via API calls to add them into the organization

## Installation

1) Clone the repo using git commands and checkout the master or develop branch.


```bash
git fetch && git checkout -f origin/{YOUR_BRANCH}
```

2) Make sure mysql is installed with mysql command line tool and create a database with user having access specific to this database only.

3) Copy .env.example file and update the ones [ Not exclusive ]

```env
APP_URL

DB_DATABASE
DB_USERNAME
DB_PASSWORD

MAIL_READ_DAYS
BROADCAST_DRIVER
QUEUE_DRIVER

MAIL_DRIVER
MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_PASSWORD
MAIL_FROM_NAME
MAIL_FROM_ADDRESS

QUEUE_SHELL=shell_emails
QUEUE_IMMEDIATE=shell_emails_immediate
APP_TIME_ZONE=UTC
```

## Setup

Note : All the commands are run once you are in the project directory :)

1) Install the vendor files using
```bash
composer install --ignore-platform-reqs
```

2) Install frontend Gulp dependencies

```bash
npm install
```
3) Install Bower frontend dependencies

```bash
bower install
```

4) Make the database up and running 
```bash
php artisan migrate
```

5) Run the Gulp Build command to compile all the assets
```bash
gulp build
```

6) Setting up Queues and Workers
We are using **Database** as a **queue** instead of **Reddis**

    ##### Local Environment
    Queue is Database setup in .env file.

    Run the following to start a worker for Database queue with two different queues used in the Codebase [shell_emails_immediate,shell_emails]

    ```bash

     php artisan queue:work database --tries=1 --queue=shell_emails_immediate,shell_emails

    ```
    
    ##### Stage/Production
    
    ```bash
    pm2 start laravel-queue-worker.yml 
    ```
    
    ##### Logging
    
    ```bash
    pm2 log
    ```
    
    ##### Restarting
    
    ```bash
    pm2 restart all
    ```
    
    ##### Kill and Start [ Needed when the paths have changed, or processes do not start/show errored ]
    
    ```bash
    pm2 kill
    pm2 start laravel-queue-worker.yml 
    ```
    
    If used supervisor, please follow the following :   
    [Guide in Larvel 5.4 Documentation](https://laravel.com/docs/5.4/queues#supervisor-configuration)
