# Open Badges Framework General Documentation

Badges for Languages is an on-line certification issuer with [Open Badges](https://support.mozilla.org/en-US/products/open-badges/introduction-open-badges "Intro to Open Badges") technology. Badges for Languages issue non official certifications for all the languages that exist for the C.E.F.R for Languages levels, and certifications for the teachers' levels.

Open Badges Framework is created by Badges for Languages for second languages learning,but out of the box works for any type of education by just creating different badges (different Fields of education and Levels).

## About Open Badges Framework Plugin
The aim of Open Badges Framework is to accomplish:
* **One common worldwide standard for each field of education**

  All the certified teachers follow the same educational recommendations

* **Lower price**

  Due to the standardization, Students no longer need to pay more for official exams.

* **Profile of the Reputation**

  Rating and Reviews for Teachers and Classes

* **System of Trust**

  Data logger recording online to see all of the information about Teachers, Students and their Classes.

### [Open Badges Framework](https://github.com/Badges4Languages/OpenBadgesFramework/releases) key features

* Teachers can to send digital certifications to Students or Teachers
* Save the information (Student name/email, Level, Lesson name, Date, etc)
* Students can to write a comment to the teacher Classes and receive an answer.
* Users can collect the certifications in their profile

Open Badges Framework integrate in one place several digital tools for Language Examiners.

### Open Badges Framework all the features

* **Acceptation Badge email**

  By default, any user receives an email with the Badge from the Teacher.

* **Easy registration**

  By default, any user is a Student by receiving the acceptation Badge email.

* **Open Badges API integration**

  Backpack integration with the acceptation Badge email

* **Students' and Teachers' Log**

  Save the information of your Badges, Classes and rating in the profile, forever.

* **Different Roles**

  Students, Teachers and Academies have different permissions for each specific role.

* **Manual Academies Role approval**

  Only real Teachers receive the Academy Role and unlock the extended features.

* **Update of information not allowed**

  Neither teachers of students can delete information.

* **Unlimited creation of badges**

  Academy teachers role can create unlimited number of badges for specified purposes.

    - **Badges for Students**

      Use badges alienated with official educational standards.

    - **Badges for Teachers**

      Use Badges for professional life long learning.

* **Certified Badges**

  Academy Role Teacher can offer certified Badges with the endorsement of B4L.

* **Taxonomy for the Badges**

  Fields of education and level taxonomies.

* **Different possibilities for sending the Badges**

  - **Self Badge issue**

    Teachers or Students can give to themselves a Badge for their skills

  - **Single Badge issue**

    Teachers send Badges to the Students (one Student at a time).
    You can send a badge to each of your students, one at a time.

  - **Multiple Badges issue**

    Academy Role teachers send Badges to the Class (multiple Students at once).
    You can send multiple badges to all your students, at the same time.

#### Soon

* **Ratings and Review of the teachers and classes**

  - **Class Review**

    Students can write feedbacks for the classes.

  - **Answer the Review**

    Teacher can answer students' reviews.

  - **Teachers' Rating**

    Students can rate teacher's knowledge of the language, and their teaching skills.

  - **Class Rating**

    Students can rate classes (methodology, installations, price..)

* **Official examinations**

  Following MCER recommendations, the Academics can offer an international examination.

#### Badges4Languages specifical settings

 * **CEFR Badges**

	Use badges alienated with CEFR for languages (A1, A2, B1, B2, C1 and C2).

 * **Badges for teachers**

	Show your level of expertise for the language you teach (T1, T2, T3, T4, T5 and T6).

 * **All of the languages**

	In all of the 7.306 languages, if we are missing one, let us to know.


## Complements

### Theme
[Listify](https://themeforest.net/item/listify-wordpress-directory-theme/9602611?ref=colomet)
 is a top rated and popular directory theme. It’s compatible with several WordPress Plugins (the WP Job Manager and WooCommerce Plugins are required with the theme) and booking services as well. To add to this, [Astoundify provides detailed documentation](http://listify.astoundify.com/)
, and several hooks and settings to customize the look of the theme.

To be clear, the theme doesn’t come bundled with these Plugins. You’ll have to download them yourself (and some may require a purchase). What we’ve done, however, is code the theme to support and style the integration with those Plugins so that you can add all the functionality you like, while still looking great!

Open Badges Framework works with any theme. But we did integrate with several Plugins and Listify in order to be able to enhance the power of the Plugin. Thanks to those complements, Badges4Languages offers a listing/directory for teachers and their classes, charge for the classes, and much more. With Listify, Open Badges Framework:
* Offers a directory of Classes (for Academies, Schools, Teachers,)
* Organizes the students by Classes (Unlimited classes)
* Offers profiles (for Teachers and their Academies/Schools)
* Offers rating (for Classes and Teachers)
* Offers advanced filter system (Search teachers and classes)

### Plugins
The Plugin integrates with the following Plugins right out of the box:

[WP Job Manager (free)](https://wordpress.org/plugins/wp-job-manager/),
[Reviews for Job Manager (free)](https://astoundify.com/products/wp-job-manager-reviews/),
[Porfolio (by Badges4Languages)](https://github.com/Badges4Languages/).

#### Portolio
The Portfolio is a personal document of a learner. In this document learners of all ages can record their learning and cultural experiences in class or outside class.

* Teacher Porftolio (Full description of Teachers' skills)
* Student Portfolio (Full description of the Students' learning process)
* Upload credential to the portfolio to prove your knowledge and experience

## Open Badges
Open Badges are verifiable, portable digital badges with embedded metadata about skills and achievements. They comply with the Open Badges Specification and are shareable across the web.

Each Open Badge is associated with an image and information about the badge, its recipient, the issuer, and any supporting evidence. All this information may be packaged within a badge image file that can be displayed via online CVs and social networks. Thousands of organizations across the world issue badges in accordance with the Open Badges Specification, from non-profits to major employers to educational institutions at all levels.

[What’s an Open Badge?](https://openbadges.org/get-started/)

### Developers Guide
Badges for Languages use [Mozilla's Open Badges Infrastructure (OBI)](https://support.mozilla.org/en-US/kb/what-open-badges-infrastructure "What is the Open Badges Infrastructure?") witch provides the open, core technology to support the ecosystem of badges.

The badges go from the Plugin to the [Backpack](https://site.imsglobal.org/certifications?refinementList%5Bstandards_lvlx%5D%5B0%5D=Open%20Badges#backpacks) of choice. 
Currently, the plugin usses the [Badgr](https://badgr.com/backpack/) Backpack service and API calls.

This section provides a set of technical resources to guide you through the processes of creating, issuing and displaying Open Badges. The Specification provides technical documentation and code examples. These guides will build on those examples.

[Developers Guide](https://openbadges.org/developers/)

[Open Badges Specification V2.0](https://www.imsglobal.org/sites/default/files/Badges/OBv2p0/examples/index.html "Open Badges v2.0 IMS Candidate Final")

[Open Badges Assertion Specification](https://github.com/mozilla/openbadges-specification/blob/master/Assertion/latest.md "Assertion Specification")

### open badges backpack
With the Issuer API, you can push earner badges to the Mozilla hosted Backpack. This tutorial will walk you through the process of sending earner badges to the Backpack via the Issuer API script. The API handles getting the earner's permission to push to their Backpack, so your own code only has to pass the details of badges you are trying to send, then if necessary retrieve the response from the API.

[Using the Issuer API](https://github.com/mozilla/openbadges-backpack/wiki/using-the-issuer-api)

As an issuer, you can push earner badges to the Mozilla hosted Backpack with their permission. You can do this using the Issuer API or using Backpack Connect. This guide demonstrates using the Backpack Connect approach to send earner badges to their Backpacks.

With the Issuer API, the user must grant permission every time you attempt to push to their Backpack. With Backpack Connect, you manage user permission on an ongoing basis, using a persistent session. Once the earner has granted you permission to push badges to their Backpack, you will be able to do so without their interaction unless they revoke your permission, which they can do any time.

To manage your interaction with the earner Backpack, the Connect API uses access tokens.

 [Using the Backpack Connect API](https://github.com/mozilla/openbadges-backpack/wiki/using-the-backpack-connect-api)

## Badgr API Platform
The previous section explains the format of how the badges are created and stored in our back-end process.
After Mozilla retired its Open Badges Backpack platform, the company decided to integrate the Badgr API platform into the plugin for the process of issuing badges and storing them. The following section will explain the basic structure of the usage of the Badgr API.

### Developers Guide
All necessarry information about using the new Badgr API, as an issuer or user can be found [here](https://badgr.org/app-developers/api-guide/). 
This guide explains the process of Authentication and how Tokens are used in the whole process of the badge exchange. 
It also explains the process of creating the Issuer, BadgeClass and Assertion Object formats, providing examples of usage.

[Badge API references](https://api.badgr.io/docs/v2/).

### Technical strcuctures
All objects of Assertions and badges created with the previous API are stored and can be reused as the new update of the plugin takes the stored badge and assertion information and restructures it for the new API format requirements when a badge is being accepted by the recipient of the badge (after the user received the email awarding him the badge).

As an issuer of a badge, you must follow these steps in creating a new account as an issuer in order to be validated and authenticated. In addition, any future issuing of the already established issuer will be considered valid when a special key for authentication is used, which is mandatory for every request to the Badgr backpack service.

[Badgr Connected Application](https://badgr.org/app-developers/) 

### Plugin Integration
The plugin integration with the Badgr platform is really simple. When a user recieves an email with an award about a badge then the user is redirected to page where then, if the user chooses to receive the badge on the backpack too, a set of requests are send to the [Badgr EU Server](https://eu.badgr.com/) to validate this action and finally award the badge to the backpack of the recipient.

This process goes though many steps for validation and authentication of the action of awarding the badge to the recipient's backpack. 

* Token: Used to authenticate all future requests done to the Badgr API from the corresponding issuer profile. If the file that contains this Token doesn't exist then it will be created, restarting the whole process of issuer creation from the start This should be created automatically on the first attempt to get a badge on the Badgr platform. Uses credentials from admin settings page to activate this.

* Issuer: Used to issue all requests, trusted entity with Authorization Token for awarding badges to recipients. A check is done if it exists as an official Issuer to the appropriate server. A new Issuer profile is created if it doesn't exists (Second step when issuing the first badge as a new Issuer).

* Badge Class: The badge class of the badge being awarder. It is send to the recipient's backpack using the issuer's authorization token. If a badge class doesn't exists then it is created and issued (an existance check is made for each badge before awarded).

* Assertion: The final step of the badge awardness to the recipient's backpack. A thorough check is made here to make sure that this particular badge was not awarded again to the current recipient. After this, the badge is succesfully awarded to the user.

All information for Issuer, Badge Classes and Assertions issued are stored on a file, locally created for the plugin to update for each new assertion.  

---
Back to [Readme](../README.md).
