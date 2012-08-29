Plex Over (Reloaded)
====================

Get You Plex Content within a web browser.

### Server side requirements:

-   A web server
-   PHP 5.3 or above
-   PHP “short\_open\_tag” directive enabled (in php.ini)
-   PHP Curl
-   PHP Sqlite
-   Plex media server running on the same machine

### Client side requirements:

-   A (d)(r)ecent web-browser which supports html5 and css3 (Safari is
    required for video transcoding)
-   A MyPlex account for authentication

Installation and Configuration
------------------------------

1. Copy the whole content somewhere on your computer, where “public”
folder can be served.

2. Open app/config/main.php and set required infos:

-   **host**: PMS hostname (localhost by default)
-   **port**: PMS port number (32400 by default)
-   **protocol**: PMS protocol (http by default)
-   **identifier**: Machine identifier, required for MyPlex
    authentication (see http://localhost:32400 at machineIdentifier key)
-   **databases**: path to the Plex Media Server sqlite databases
    folder. Default location is app/database/.  
     In order to access them, you can create aliases with ‘ln’ command:
    (example on Os X)  

    `ln /Users/you/Library/Application\ Support/Plex\ Media\ Server/Plug-in\ Support/Databases/com.plexapp.plugins.library.db /path-to-app/databases/`

Other parameters can be set from Plex Over once you’re logged in.

File Permissions
----------------

Plex Over requires write access on following directories:

-   app/cache
-   app/logs
-   app/tmp

Because Plex Media Server seem’s to not reference automatically
subtitles, you may need to allow read access to them.  
 Plex Over tries to grab subtitles with the same name of the media, with
.srt extension

Roadmap
-------

-   **Trancoding**: Audio / Video Flash support (contributions are more
    than welcome)
-   **Plugins**: API and documentation, to allow people to write and
    install third party plugins
-   **Authentication**: Write a local config based authentication driver
-   **User Interface**: Improve many things…
-   **Subtitles**: Implements opensubtitles API in order to retrieve missing subtitles