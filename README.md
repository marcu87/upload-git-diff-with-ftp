# Features
Using a little webinterface, this script allows you to upload automatically the git-diff to a FTP server.

# How use this?
First in you GIT project, generate a git-diff, example:

`git diff --name-status master issue_1063`
```
M       trunk/web/Controllers/back/CMS/stats/index.php
M       trunk/web/Views/skins/back/Pages/share/full_layout.css
A       trunk/web/Views/templates_dir/templates/Pages/back/CMS/_Misc_/statsMenu.tpl
M       trunk/web/Views/templates_dir/templates/Pages/back/CMS/stats/index.tpl
```
Then copy the git-diff result, and paste into the program page, select the server where you want to upload all the changes, and click Send.

That's all, all the files listed will be uploaded to the server that you choosed.

# Configuration
Open the index.php and change the server for your server configuration.
Change the variable $localPath and $remotePath
Modify the $sanatization variables also, that is the the replazment of the url's to clean the path of your files.

# Download
Click here to download

_And that's all, you are ready to start uploading!_
