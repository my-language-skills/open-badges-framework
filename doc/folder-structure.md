# Theme structure
```
Plugins/OpenBadgesFramework/                    # → Plugin root
├── assets/                                     # → Resources folder
│   │── css/                                    # → Css folder
│   │   │── get-badge.css                       # → Get badge css files
│   │   │── obf-style.css                       # → General style
│   │   └── send-badge.css                      # → Send badge style
│   │── gif/                                    # → Gif folder
│   │   └── loading.gif                         # → Loading gif for Send badge page
│   │── images/                                 # → Images folder
│   │   │── default-badge.png                   # → Default badge image  
│   │   │── logo.png                            # → Logo of the official plugin
│   │   │── open-badges-mz-logo.png             # → Logo of the Mozilla Open Badge
│   │   │── open-badges-mz-logo-header.jpg      # → Logo of the Mozilla Open Badge (secondary)
│   │   │── open-badges-mz-logo-header2.jpg     # → Logo of the Mozilla Open Badge (secondary)
│   │   └── open-badges-mz-logo-header-EID.jpg  # → Logo of the Mozilla Open Badge (secondary)
│   │── js/                                     # → Js folder
│   │   │── get-badge.js                        # → Contain Get Badge page code
│   │   │── jquery.steps.min.js                 # → Contain Send Badge steps code
│   │   │── obf-script.js                       # → General script
│   │   └── send-badge.js                       # → Contain Send Badge page code
│   │── inc/                                    # → Plugin core folder
│   │   │── Ajax/                               # → Ajax functions folder
│   │   │   │── AdminAjax.php                   # → Ajax class for general things of the plugin
│   │   │   │── GetBadgeAjax.php                # → Ajax class for the Get Badge page
│   │   │   └── SendBadgeAjax.php               # → Ajax class for the Send Badge page
│   │   │── Api/                                # → Api folder
│   │   │   │── AjaxApi.php                     # → Folder for initialize and load all the ajax functions
│   │   │   └── SettingApi.php                  # → Permit to load all the wordpress component
│   │   │── Base/                               # → Basic classes folder
│   │   │   │── Activate.php                    # → Contain code that execute at the activation
│   │   │   │── BaseController.php              # → Retrieve information about paths and urls
│   │   │   │── Deactivate.php                  # → Contain code that execute at the deactivation
│   │   │   │── Enqueue.php                     # → Load all the styles and scripts
│   │   │   │── Metabox.php                     # → Contain code to create metaboxes
│   │   │   │── Secondary.php                   # → Allow to add feature to the plugin
│   │   │   └── SettingsLinks.php               # → Create the setting link for the plugin admin page
│   │   │── Database/                           # → Database classes folder
│   │   │   │── DbBadge.php                     # → Manage the badges database table
│   │   │   │── DbModel.php                     # → Parent class for management of the database
│   │   │   └── DbUser.php                      # → Manage the user database table
│   │   │── Pages/                              # → WordPress component folder
│   │   │   └── Admin.php                       # → Contain all the WordPress component (Sub-pages, CPT, ...)
│   │   │── Utils/                              # → Utils folder
│   │   │   │── Badge.php                       # → Contain code for the managment of the OBF DB, table user
│   │   │   │── DisplayFunction.php             # → Class for display something of generic
│   │   │   │── JsonManagement.php              # → Contain code for the management of json files
│   │   │   │── SendBadge.php                   # → Contain code for the management of the send badge process
│   │   │   │── Statistics.php                  # → Contain code for statistical purposes
│   │   │   │── WPBadge.php                     # → Contain code for the management of the badges
│   │   │   │── WPClass.php                     # → Contain code for the management of the classes
│   │   │   │── WPField.php                     # → Contain code for the management of the fields
│   │   │   │── WPLevel.php                     # → Contain code for the management of the levels
│   │   │   └── WPUser.php                      # → Class for the management of the users
│   │   └── Init.php                            # → The first class that make start all the process
├── languages/                                  # → Translation folder
├── templates/                                  # → Template pages folder
│   │── BadgesTemp.php                          # → Provide to shows all the badgea created
│   │── DashboardTemp.php                       # → Provide an admin area view
│   │── GetBadgeTemp.php                        # → Provide a get badge template
│   │── SendBadgeTemp.php                       # → Provide a send badge template
│   │── SettingsTemp.php                        # → Provide a settings template
│   └── UserTemp.php                            # → Provide a user template
├── vendor/                                     # → Composer folder for the autoloading of the files
├── README.md                                   # → Read-me file .md
├── Readme.txt                                  # → Read-me file .txt
├── composer.json                               # → Composer file for the autoloading of the files
├── index.php/                                  # → Silly page for wordpress
├── open-badges-framework.php                   # → First php file called from WordPress
└── unistall.php                                # → Contain code that's execute at the uninstallation
```

---
Back to [Readme](../README.md).
