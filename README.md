# VWM Secure Files

## Release Notes

### Version 0.3 ###

* Actually changed how download limit works (I only changed it in the upload SQL last version...)
* Can now add a folder as a secure file and then pass a second URL param to link to a file

### Version 0.2 ###

* Changed how download limit works (a limit of 0 is no longer "unlimited downloads", now you must clear that input to make it truly unlimited)
* Make sure to update all your files with a limit of "0" to be an empty string "" if you want the download limit to be unlimited
* Make sure to run the module updater to make this change in the database

## About

VWM Secure Files allows you to restrict access to files based on a given members group and/or member ID. It includes a module and and a simple fieldtype component.

## What it Does

Assume you have the super secret file `confidential.txt` in the folder `/secret_documents`. Normally, anyone can go to `http://example.com/secret_documents/confidential.txt` to download your file.

With VWM Secure File (and some .htaccess help) you can lock this file down so only certain people can download your secure file. The URL to access the file even becomes masked to look something like `http://example.com/?ACT=2&ID=7d4fb3c3517888bbc06eaa57b3f02788`.

## Features

* Restrict downloads by member group
* Restrict downloads by member ID
* Limit total number of downloads
* Masks file name by using unique MD5 hash

## How to Use (server config)

1. Create a folder on your server in order to place your uploaded files
2. Make sure permissions are 777
3. Place .htaccess file inside with following code
4. If you plan on accessing files on remote servers make sure [allow_url_fopen](http://www.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen) is set to `TRUE` on the remote server

```
order deny, allow
deny from all
```

## How to Use (module installation)

1. Copy `vwm_secure_files` folder into your `system/expressionengine/thirdparty/` folder
2. Login to EE Control Panel
3. Go to *Add-Ons* > *Modules* section
4. Click on the *install* link next to *VWM Secure Files*
5. Select the *Install* radio input for both the fieldtype and the module
6. Click the *Submit* button

## How to Use (getting it done)

1. Go to *Add-Ons* > *Modules*
2. Click on *VWM Secure Files*
3. Enter file path to file you want to lock down (a path relative to your EE `index.php` **should** work, otherwise an absolute server path may be necessary)
4. Select member and group permissions
5. Click *Add Secure File* button

## How to Use (getting it done with secure folders)

A new feature to version 0.3 is the ability to add a folder as a secure file and then pass a second URL param to link to the file. Assume you add the secure file `uploads/` (Make sure to add the trailing `/`!). You can then append the URL param `file_path` to link to any file inside the `uploads` directory. The URL for this file could look something like this:

````
http://example.com/index.php/?ACT=24&ID=54d1f4645ee8d994a91ece3b6181c093&file_path=i_am_awesome.jpg
````

And if the user decides to get tricky and do some URL traversal he will be sorely disappointed for I am harnessing the power of `str_replace()` to remove all `../` and `..\`.

## Some Notes

* After a file is added in the VWM Secure Files module, clicking the *Remove* button will only remove the **link** to the file, *not* the file itself
* Member and group permissions are processed from left to right (the permissions set to the right override those set on the left)
* This module will most likely totally change once ExpressionEngine 2.2 rolls out with the improved file manager

## License

VWM Secure Files is licensed under the [Apache 2 License](http://www.apache.org/licenses/LICENSE-2.0.html).