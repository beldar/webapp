Mobile Web App
==============

Just a test I did for a Whatsapp-like chatterbot mobile web app, thought for a Volkswagen Polo campaign.
For educational purposes only.

You can check out some screenshots [HERE](https://www.dropbox.com/sh/7y8i20hpql8uxbx/tCgd7VFy81)

Quick Start
-----------

So you want to test this? You will need a database then, for the Chatterbot AIML part use the file on programe/admin/db.sql to create
the database and edit the programe/admin/dbprefs.php, once you created the structure, you can edit the programe/aiml/startup.xml if you want and then  you must go to /programe/admin/botloader.php to populate the database,
you can edit or change the aiml files on programe/aiml folder.

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
    ) ENGINE=InnoDB;

If you want to test the Log In with Facebook and/or Twitter you will need a Facebook and a Twitter App, and configure the App ID, Secret, ... on the file hybridauth/config.php

And you're ready to go!

Templating
----------
I'm using a custom-made simple templating to insert images and video on the chat, you can check the programe/aiml/test_new.aiml to see how to use it,
but in a nutshell if you want to include and image you do it like this:

    {image:image.jpg}

And for a video:

    {video:video.mp4}

The poster image for the video is the same name with .jpg extension (in this example video.jpg)
All the files must be on the img/files folder. But you can change this route on the programe/util.php file (line 1013).

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
* Image and video template.

All the images used here are Copyright of Carrots.