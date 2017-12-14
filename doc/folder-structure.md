# theme structure
```shell
Plugins/OpenBadgesFramework/                    # → Plugin root
├── assets/                                     # → Resources folder
│   │── css/                                    # → Css folder
│   │   │── get-badge.css                       # → Get badge css files
│   │   │── mystyle.css                         # → General style
│   │   └── send-badge.css                      # → Send badge style
│   │── gif/                                    # → Gif folder
│   │   │── circle-loading.gif                  # → Loading gif for Send badge page
│   │   │── mixed-loading.gif                   # → Loading gif for get badge page
│   │   └── vertical-loading.gif                # → Loading gif for get badge page
│   │── images/                                 # → Images folder
│   │   │── default-badge.png                   # → Default badge image
│   │   │── logo.png                            # → Logo of the official plugin
│   │   └── open-badges-mz-logo.png             # → Logo of the Mozilla Open Badge
│   │── js/                                     # → Js folder
│   │   │── general.js                          # → Contain simple code
│   │   │── get-badge.js                        # → Contain Get Badge page code
│   │   │── jquery.steps.min.js                 # → Contain Send Badge steps code
│   │   └── send-badge.js                       # → Contain Send Badge page code
│   │── inc/                                    # → Plugin core folder 
│   │   │── Ajax/                               # → Ajax functions folder
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
│   │   │   │── SettingsLinks.php               # → Create the setting link for the plugin admin page
│   │   │   └── User.php                        # → Class for the management of the users
│   │   │── Database/                           # → Database classes folder
│   │   │   │── DbBadge.php                     # → Manage the badges database table
│   │   │   └── DbModel.php                     # → Parent class for management of the database
│   │   │── Pages/                              # → WordPress component folder
│   │   │   └── Admin.php                       # → Contain all the WordPress component (Sub-pages, CPT, ...)
│   │   │── Utils/                              # → Utils folder
│   │   │   │── Badges.php                      # → Contain code for the management of the badges
│   │   │   │── Classes.php                     # → Contain code for the management of the classes
│   │   │   │── DisplayFunction.php             # → Class for display something of generic 
│   │   │   │── Fields.php                      # → Contain code for the management of the fields
│   │   │   │── Levels.php                      # → Contain code for the management of the levels
│   │   │   └── JsonManagement.php              # → Contain code for the management of json files
│   │   │   │── SendBadge.php                   # → Contain code for the management of the send badge process
│   │   │   └── Statistics.php                  # → Contain code for statistical purposes
│   │   └── Init.php                            # → The first class that make start all the process
├── languages/                                  # → Translation folder
├── templates/                                  # → Template pages folder
│   │── DashboardTemp.php                       # → Provide an admin area view
│   │── GetBadgeTemp.php                        # → Provide a get badge template
│   │── SendBadgeTemp.php                       # → Provide a send badge template
│   └── SettingsTemp.php                        # → Provide a settings template
├── vendor/                                     # → Composer folder for the autoloading of the files
├── README.md                                   # → Read-me file .md
├── Readme.txt                                  # → Read-me file .txt
├── composer.json                               # → Composer file for the autoloading of the files
├── index.php/                                  # → Silly page for wordpress
├── open-badges-framework.php                   # → First php file called from WordPress 
└── unistall.php                                # → Contain code that's execute at the uninstallation
