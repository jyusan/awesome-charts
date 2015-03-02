# awesome-charts
Web solution for visualizing Awesomenauts character stats. Uses PHP (5.2+), MySQL, JQuery, HTML5, CSS and Javascript. The MySQL database stores the top 5000 ranked users' character preferences.

## Setup
1. Not included in the repo: `config` folder needs to include 
  1. a `.connection.php` script with variables storing the MySQL connection configuration (for what's needed check `db.php`) and
  2. a `.key.php` file for the admin key needed (`$admin_key`) for some admin features.
2. The folder `db_scripts` contains the SQL scripts for initializing the MySQL tables.
3. To save historic data for finished seasons run `init.php?key=ADMIN_KEY`. If the script runs too long, adjust the loop.
4. Make sure `update_current.php?key=ADMIN_KEY` is called so that the current leaderboard's data is loaded as well.

## Data update
To keep data up-to-date, there are to scripts that can be called in automated cron jobs:
* `update_current.php` fetches the data of the current season. Usual runtime on my test server is a few seconds, can be run every 5 minutes or so.
* `update_historic.php` script checks whether the last finished season's historic data has been saved. Should run after the end of every season, or on the first day of every month (not sure when the leaderboard switch happens, timing needs to depend on that)

## Other notable scripts
* `db.php` stores the DB singleton object that does all the MySQL data access
* `data_functions.php` has all the convenience functions
* `*chart.php` scripts are for displaying the charts and some raw statistics.

## Credits
* [CanvasJS](http://canvasjs.com/)
* [DataTables](http://www.datatables.net/)
