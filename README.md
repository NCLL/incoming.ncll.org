#Introduction

This repo/site handles incoming lead generation from outside advertising.

#Usage

The `example.php` file can be duplicated and reused for new pages as needed. Processing for all forms are handled by `process_form.php`, which uses [eTapestry’s API](https://www.blackbaudhq.com/files/etapestry/api/basics.html) to add new contacts to the database.

#Note

Currently the NCLL eTapestry account is on [version 2](https://www.blackbaudhq.com/files/etapestry/api/basics.html) of the API. When the account is upgraded to [version 3](https://www.blackbaudhq.com/files/etapestry/api3/basics.html), change `$api_version` to 3 in `process_form.php`.
