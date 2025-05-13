<h3>Setup Guide : </h3>
<p>- Clone the project from the repository. </p>
<p>- Install the composer in the project directory. (composer install) </p>
<p>- Install default authentication by  (php artisan breeze:install blade)</p>
<p>- Make the .env file by copying the .env.example file. </p>
<p>- Generate the unique application key in .env. (php artisan key:generate) </p>
<p>- Migrate the tables. (php artisan migrate) </p>
<p>- Seed the tables. (php artisan db:seed) </p>
<p>- Install npm or yarn dependency manager. (npm install OR yarn install) note: yarn prefered </p>
<p>- To compile all the CSS and JS file execute the command. (npm run dev OR yarn run dev) </p>
<p>- Open crontab by running , run the command (crontab -e) </p>
<p>- add this line and save  * * * * * php /Applications/MAMP/htdocs/testcap/laravel11-app/artisan schedule:run >> /dev/null 2>&1 </p>
<p>- Run the schedular (php artisan schedule:run) in terminal to hit the api and store the data in db </p>
