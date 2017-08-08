# Badges-Issuer
A WordPress plugin for issuer language badges

Contributors: @colomet, @ntorion, @alevacher

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

### now
 * Some modifications in class job listing template
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
