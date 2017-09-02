# Badges-Issuer
A WordPress plugin for issuer language badges

Contributors: @colomet, @ntorion, @uzair043, @CorentinPerrot, @alevacher

Tags: badges

## Description

Open Badges allows you to distribute and receive certifications of nivel language skills.
Badges-Issuer extends this possibility by giving the proof that your certification corresponds to your nivel.

## Installation

1. Clone (or copy) this repository to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' screen in WordPress

## Frequently Asked Questions

## Requirements

Plugin/Template Name works with:

 * ![PHP](https://img.shields.io/badge/PHP-7.X-blue.svg)

## Disclaimers

The Badges-Issuer plugin is supplied "as is" and all use is at your own risk.

## Screenshots

You can see all of the screenshots of the plugin [here](doc/screenshot.md "screenshots")

## Roadmap

### 0.x

 * **ADDITIONS**

 * **ENHANCEMENTS**
  * New images/ images optimization

 * **BUGFIXES**
  * Correct version number of release

### now
 * To create students and teacher page

### soon
 * To review the classes
 * Inform of the process to the senders of badges
 * Add a section in settings page to manage time for commenting a class
 * Replace students' login by mail in the user profiles and class pages
 * Description metabox - txt with translations
 * Recomended resolution for cover image in "post class" page
 * To use schema metadata for the badges information
 * Fixing spam problem with email
 * To delete all the information of the plugin option if we wish by deleting the plugin

## Changelog

### 0.6.2

* **ENHANCEMENTS**

 * To save more information in the student profile
 * Criteria section
 * Fix images folder and pages for frond end in wrong place
 * to show the link of the badge in the email to the students
 * Show description in send badges form

### 0.6.1

* **ADDITIONS**
  * New section in Settings page for changing register and login links.
  * One criteria file for each level.

* **ENHANCEMENTS**
  * Deselect input radio in selection of classes in send badges page by clicking on reset link.
  * Links for displaying all categories of languages
  * In CPT class to show more information in the reviews
  * Multiple reviews in the same class forbidden.
  * Send badge page integration with the CPT job manager.
  * New email organization.

* **BUGFIXES**
  * Fix pages for get the badge

### 0.6

* **ADDITIONS**
  * Add messages to get_badge page (if user has already received this badge, inform user that he will be able to review the class after he received the badge).
  * Save name of class in user profile (section badges).
  * Editor role has same permissions than the Administrator.
  * Show badge title in send badges page when the badge is selected.
  * Add register link into get_badge page when the user is not logged.

* **ENHANCEMENTS**
  * Increase text size in delete_badge page.
  * Show "Translations of description" title oly when translations exist in class template.
  * Remove default badges issuer informations.
  * Send badges page and forms more simple.

* **BUGFIXES**
  * Fix message when user is not logged in the get_badge page.
  * Fix teacher shouldn't be able to see multiple send badges tab.
  * Fix check of roles.

### 0.5.3

* **ENHANCEMENTS**
  * The send badges page is organized with tabs (change front-end style of tabs).
  * The form for sending badges is more simple (with steps).
  * The user is redirected to the class after that he received the badge in his backpack account.

* **BUGFIXES**
  * Fix relative url (for json files directory) in send-badges.php.

### 0.5.2

* **ADDITIONS**
  * A default image for representing a badge is displayed when the badge don't have an image.

* **ENHANCEMENTS**
  * Administrator can select the zeros classes of all teachers in the send badges page.
  * The useless metabox descriptions in badge post page has been removed.

* **BUGFIXES**
  * The bug in the send badges form when the badge name is a number is fixed.

### 0.5.1

* **ENHANCEMENTS**
  * Remove job listing metaboxes level and language.

* **BUGFIXES**
  * Add verification on meta values before access.

### 0.5

* **ADDITIONS**
  * Move descriptions into the content of the badges post. And teachers of academy can add translations of these descriptions directly in the comments of the badges post.
  * Teachers of academy are able to modify their own translations of descriptions.

* **ENHANCEMENTS**
  * In the send badges page, certified badges are separated from the others badges.
  * Some change of elements' name as written in documentation.md file.

### 0.4.1

* **ADDITIONS**
  * Add of a section in the settings page for managing the links of the website.

* **ENHANCEMENTS**
  * Administrator can add or delete students from classes.
  * System of trust improved : only students of a class can leave a review and a comment, and the teacher of the class is able to answer.
* **BUGFIXES**
  * Roles and capabilities.

### 0.4

* **ADDITIONS**
  * "Can take few seconds to load" message for display all languages link.

* **ENHANCEMENTS**
  * Change metabox levels into taxonomies.
  * Move field of education taxonomy into Badge CPT.
  * Class CPT page into Badge CPT menu.

### 0.3

* **ADDITIONS**
  * Plugin can work without job manager.
  * One place all data : all students of a teacher stocked in his class zero.
  * Integration of translations.

* **ENHANCEMENTS**
  * Languages form more smart : display in first time the most important languages.
  * Amelioration of the settings page.
  * Possibility to read the information of the badges translated inside of the custom post.

### 0.2

* **ADDITIONS**
  * Badges issuer in the front-end (works with a shortcode). #5
  * Introduction of WP roles : Student, Teacher and Academy. Possibility to restrict the content for each role. #6
  * Links of bibliography in the badges custom post type. #7
  * Inform the news users that they have to open a open badges account. #18
  * Information in the database as a custom post type. #19
  * Use of job listing type of WP Job Manager plugin for the classes.
  * Stock of the students' informations in the class type (job listing type).
  * Creation of a settings page in the class type (job listing type).
  * Possibility to add a student to a class in the send badges form.
  * Create automatically the badges issuer json file in the "updates" directory.

* **ENHANCEMENTS**
  * Simplification of code. #22

* **BUGFIXES**
  * Undefined variable in javascript code.

### 0.1

* **INITIAL VERSION**
  * Creation of the Custom Post Type 'Badge School'.
  * Possibility to create the information of the badge inside of the badges custom post type page.
  * Gestion of badges' description (and translations) inside txt files for an easy use.
  * Creation of admin subpages to send a badge to one or several students.

## Upgrade Notice

### 0.6

Plugin more stable, more ergonomic and more simple.

### 0.5

Translations of badges descriptions easier to add.

### 0.4

Plugin better organized.

### 0.3

Plugin flexible : can work without WP Job Manager

### 0.2

Badges functionality

### 0.1

Starter

## Credits

Here's a link to [Plugin Boilerplate](http://wppb.io/ "Uses the WordPress Plugin Boilerplate")

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software")

Here's a link to [OpenBadges](http://openbadges.org/ "Mozilla Open Badges official site")
