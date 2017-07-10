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

 * ![PHP](https://img.shields.io/badge/PHP-5.6.X-blue.svg)

## Disclaimers

The Badges-Issuer plugin is supplied "as is" and all use is at your own risk.

## Screenshots

You can see all of the screenshots of the plugin [here](doc/screenshot.md "screenshots")

## Roadmap

### 0.x

 * **ADDITIONS**
 
 * **ENHANCEMENTS**

### now

  * Creation of a settings page : possibility to delete all the information of the plugin if we wish by deleting the plugin
  * Add of an import buttom for the information of the badges.
  * Possibility to read the information of the badges translated inside of the custom post.
  * Creation of the basges issuer information page.

### soon

  * Creation of students and teacher pages to show their informations and badges received.

### future

  * Create a default class for each new user (each time a new user have the teacher role, a new post with the name of the user is created).
  * Teachers can atach students to inside of the classes and create new classes.
  * Students can review the classes.


## Changelog

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

### 0.2

Badges functionality

### 0.1

Starter

## Credits

Here's a link to [Plugin Boilerplate](http://wppb.io/ "Uses the WordPress Plugin Boilerplate")

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software")

Here's a link to [OpenBadges](http://openbadges.org/ "Mozilla Open Badges official site")



