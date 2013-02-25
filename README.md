Mobile Web App
==============

Just a test I did for a Whatsapp-like chatterbot mobile web app, thought for a Volkswagen Polo campaign.
For educational purposes only.

Quick Start
-----------

So you want to test this? You will need a database then, for the Chatterbot AIML part use the file on programe/admin/db.sql to create
the database and edit the programe/admin/dbprefs.php 
You would also need a table for the user data you can create it like this:

    CREATE TABLE `users` (
        `idusers` int(11) NOT NULL AUTO_INCREMENT,
        `identifier` bigint(20) DEFAULT NULL,
        `photoURL` varchar(255) DEFAULT NULL,
        `displayName` varchar(255) DEFAULT NULL,
        `firstName` varchar(255) DEFAULT NULL,
        `gender` varchar(255) DEFAULT NULL,
        `birthday` date DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `region` varchar(255) DEFAULT NULL,
        `provider` varchar(255) DEFAULT 'none',
        `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`idusers`)
    ) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

If you want to test the Log In with Facebook and/or Twitter you will need a Facebook and a Twitter App, and configure the App ID, Secret, ... on the file hybridauth/config.php

And you're ready to go!

Featuring
---------
* App Cache management
* Backbone.js Structure
* Retina images
* Offline mode
* LocalStorage data
* AIML Parser
* Chat layout
* Login with Facebook / Twitter with Hybridauth
* Add to Favorites multilanguage Bubble
* Twitter Bootstrap responsive styles
* Font Awesome icons
* iPhone5 optimized
* Photoswipe images.

All the images used here are Copyright of Carrots.