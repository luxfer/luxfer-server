# luxfer-server

The server runs the Game of Life algorithm and allows user to change its behavior.

The server is a part of the "Mystic Wall" installation realized in 2015 by Matúš Buranovský, Adam Heinrich and Jan Husák during the ITT course held by the [Institute of Intermedia](http://iim.cz) at the [Czech Technical University in Prague](http://cvut.cz).

## How to install

To install, you neeed a server with PHP and MySQL. Just copy the repository contents into server's `www` directory and run the `database.sql` script in any MySQL client to set up the DB.

The MySQL server address, username and password have to be set in file `app/config/config.local.neon`.
