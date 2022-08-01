# CDPA Records

A SilverStripe project to store the CDPA powerlifting records.

The admin section will contain model admins to manage Affiliations, Competiitions,
Lifters, Weight/Age classes, regions and results.

The front end will display simple tables of the records and allow csv files of the records to be downloaded.

The idea is for the project to be used for data entry of competiiton results and the records should be updated automatically.

# Models

The following models have been created

- **Affiliation** Stores the affiliation like World Powerlifting or IPF
- **Competition** Stores the powerlifting meet
- **LiftType** Stores the types of lift e.g raw, equipped, wraps etc
- **Lifter** Stores the lifter and their details
- **LifterClass** Stores the class a lifter can lift in with details like weight, age, standards etc
- **Record** Stores a record (by linking a LifterClass and result)
- **Region** Stores a region
- **Result** Stores the result of a meet for a lifter

# Heroku deployment

There are a lot of tutorials and documentation already for deploying PHP apps to Heroku but here are some of the more specific
Database and SilverStripe steps.

## Database

You will need to setup a ClearDB MySQL resource under the PHP app (ignite option is free but limited) under 
Settings -> Config Vars there will be a var called CLEARDB_DATABASE_URL which contains
all the details to connect to the MySQL instance like, host, username and password

## SilverStripe environment variables

SilverStripe requires environmental variables to run these can be added via Settings -> Config Vars
The full list is below

- **SS_BASE_URL** The url (including https://) to access the public site or the web address
- **SS_DATABASE_SERVER** The database server can be obtained from CLEARDB_DATABASE_URL
- **SS_DATABASE_NAME** The name of the database can be obtained from CLEARDB_DATABASE_URL
- **SS_DATABASE_USERNAME** The username for the database can be obtained from CLEARDB_DATABASE_URL
- **SS_DATABASE_PASSWORD** The password for the database can be obtained from CLEARDB_DATABASE_URL
- **SS_DEFAULT_ADMIN_USERNAME** The default email address and admin account
- **SS_DEFAULT_ADMIN_USERNAME** The password for the default admin account
- **SS_ENVIRONMENT_TYPE** if the environmet is in dev, test or live mode (optional)

When setting up the instance it is a good idea to set SS_ENVIRONMENT_TYPE to dev so you can run 
a dev/build.
A good starting place for setting up a SilverStripe installation is [located here.](https://docs.silverstripe.org/en/4/getting_started/)

## CSV Files

A load of CSV files have been created which can be imported into the Lifter class model
admin as entering data via the GUI is time consuming.
However the overrides will still need to be done manually for now.

# Disclaimer

Some of the data will be wrong for the standards as I used the CDPA records spreadsheet
so if the standard has been broken it uses the record instead and some of the data
from these spreadsheets is just wrong like the Male 3-Lift Equipped 85kg total (43kg) for example
where I have just guessed the correct standard.

# TODO / Ideas

PHPUnit tests would be good

A task to check results from openpowerlifting and notify of corrections or updates

A task to clear the records or reset them

Get the history UI working for versioned records

Setup National and international classes so that national and international records can be stored

Store membership details 
