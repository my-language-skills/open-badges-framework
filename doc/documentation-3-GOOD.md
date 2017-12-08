# theme structure
```shell
Plugins/OpenBadgesFramework/  									# → Plugin root
├── assets/                                     # → XX
│   │── css/   																  # → XX
│   │   │── get-badge.css                       # → XX
│   │   │── mystyle.css                         # → XX
│   │   └── send-badge.css                      # → XX
│   │── gif/   																  # → XX
│   │   │── 3-point.gif                         # → XX
│   │   │── load.gif                            # → XX
│   │   └── loading-circle.gif                  # → XX
│   │── images/   															# → XX
│   │   │── default-badge.png                   # → XX
│   │   │── logo.png                            # → XX
│   │   └── open-badges-mz-logo.png             # → XX
│   │── javascript/   													# → XX
│   │   │── general.js                          # → XX
│   │   │── get-badge.js                        # → XX
│   │   │── jquery.steps.min.js                 # → XX
│   │   └── send-badge.js                       # → XX
│   │── inc/   													        # → XX
│   │   │── Ajax/                               # → XX
│   │   │   │── GetBadgeAjax.php                # → XX
│   │   │   │── InitAjax.php                    # → XX
│   │   │   └── SendBadgeAjax.php               # → XX
│   │   │── Api/                                # → XX
│   │   │   │── MetaboxApi.php                  # → XX
│   │   │   └── SettingApi.php                  # → XX
│   │   │── Base/                               # → XX
│   │   │   │── Activate.php                    # → XX
│   │   │   │── BaseController.php              # → XX
│   │   │   │── Deactivate.php                  # → XX
│   │   │   │── Enqueue.php                     # → XX
│   │   │   │── SettingsLinks.php               # → XX
│   │   │   └── User.php                        # → XX
│   │   │── Database/                           # → XX
│   │   │   │── DbBadge.php                     # → XX
│   │   │   └── DbModel.php                     # → XX
│   │   │── OB/                                 # → XX
│   │   │   └── JsonManagement.php              # → XX
│   │   │── Pages/                              # → XX
│   │   │   └── Admin.php                       # → XX
│   │   │── Utils/                              # → XX
│   │   │   │── Badges.php                      # → The Badges Class.
│   │   │   │── Classes.php                     # → The Classes Class.
│   │   │   │── DisplayFunction.php             # → The DisplayFunction Class.
│   │   │   │── Fields.php                      # → The Fields Class.
│   │   │   │── Levels.php                      # → The Levels class.
│   │   │   │── SendBadge.php                   # → The SendBadge Class.
│   │   │   └── Statistics.php                  # → The Statistics Class.
│   │   └── Init.php                            # → The Init Class
├── templates/                                  # → XX
│   │── DashboardTemp.php   										# → Provide an admin area view.
│   │── GetBadgeTemp.php   											# → The Classes Class.
│   │── SendBadgeTemp.php   										# → The SendBadgeTemp class.
│   └── SettingsTemp.php   											# → The Classes Class.
├── vendor/                                     # → XX
├── README.md                 									# → XX
├── Readme.txt                 									# → XX
├── composer.json                  							# → XX
├── index.php/                  								# → XX
├── open-badges-framework.php                 	# → XX
└── unistall.php                  							# → XX
