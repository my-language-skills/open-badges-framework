=== Open Badges Framework ===
Contributors: leocharlier
Donate link: http://example.com/
Tags:
Requires at least: 4.9.6
Tested up to: 4.9.6
Requires PHP: 5.2.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
This plugin allows you to manage badges for students, teachers and academies.
 
== Description ==

Thanks to this plugin, you will be able to manage badges for students, teachers and academies. You will be able to :

* Create the fields of education ( and sub fields ) you want.
* Create badges of different levels.
* As a student, send non certified badges to yourself.
* As a teacher, send badges to yourself and your students.
* As an academy, send badges to yourself, to students and to teachers.
* Manage classes for teacher.

Users have their own profile when they connect to wordpress and can access to these functionalities. In their profile, they can provide their year of birth, location, mother tongue, different degrees (maximum of 3) and social links. They will also be able to see the badges they earned.

As a non wordpress user, you will only be able to see all the catalog od badges.
 
== Installation ==
 
1. Upload the entire open-badges-framework folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the ‘Plugins’ menu in WordPress.
 
== Frequently Asked Questions ==
 
= A question that someone might have =
 
An answer to that question.
 
== Screenshots ==
 
1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
 
== Changelog ==
 
= 1.0.1 dev =
* **ADDITIONS**
	* Add custom fields in the Restrict Content Pro regitration form (this datas are saved in the WP user profile and not in the RCP member).
	* Add custom fields in the WP profile editor.
	* Add fields in the registration form during the "Get a badge" process. #212
	* Add the 'Lost password' link to the login form during the "Get a badge" process. #216
	* Enable reCAPTCHA in the RCP registration form.
	* Add CAPTCHA in the registration form during the "Get a badge" process (with Really Simple Captcha plugin). #217
	* Add all the WP editor fields in the OBF user page (all the information is displayed).
	* Propose to add the first badge in the "All Badges" section if no one is found.
	* Load a default company logo if no one is set.
	* Responsive design.
* BUGFIXES
	* Remove the "Add Class" link in Settings->Links when the WP Job Manager plugin is not activated. #195
	* Export the custom datas created (year of birth, country, badges earned, etc.) during the WP "Export Personal Data" process.
 
= 1.0 =
* **ADDITIONS**

  * Plugin Internationalization( notes,not bug ) #220
  * Badges can be now translated #181
  * multiple emails limitation #68
  * Teachers Statistics ( Ratio ) #219
  * Basic stadistical information #67
  * To reset the badge / user database #207 #156
  * Delete users(teachers and students) intergrated with WP
  * ~~Deletion of the custom db tables when uninstall #224~~
  * Get the badge page basic personalization (+login/register) #64 #105

* **ENHANCEMENTS**
 	* Fix the email security issue #205

= 1.0 RC =
Completely overhauled design, from top to bottom. The plugin has been completely rewritten. Version 1.0 just basic functionalities.

* Aceptation badge email.
* Automatic registration to new students by receiving an aceptation badge email.
* OpenBadges API integration with Backpack integration from the aceptation badge email.
* Users profile save badges, classes and rating logs.
* Different Roles: Student, Teacher and Academy.
* Update of information not allow to users.
* Unlimited creation of badges.
* Badges for Teachers or Students.
* Certified badges just available for Academy role teachers.
* Taxonomy for the badges: Fiels of education and level.
* Send badges to Self, Single student or Multiple students.
* Settings pages.
* Integration with wp Job Manager for the creation of Classes.

== Upgrade Notice ==
 
= 1.0 =
Completely overhauled design, from top to bottom. The plugin has been completely rewritten.
