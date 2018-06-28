# Async-coordinate-finder

## Set Up:
### Server
- Upload the files in 'server' folder to the server.
- Create a new database in phpMyAdmin and import the database structure using 'coordinates.sql'
-Change your server and database information in 'dbConnect.php' to your information so that the application can communicate with the database.

### Client
All client files to be found in this folder.
To set it up you need to change the server URL to the one you will be using for the project.

## Use
- You can upload a CSV file with names and address which will generate an ID in return.
- Loading a certain id chosen from the list will tell you if the ID is ready or pending. If the job is ready it will print out the coordinates and display the coordinates on the map. If you hover over the markers given it will give you the name and address of the location. 
