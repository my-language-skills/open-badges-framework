<?php
/**
 * The Classes Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace templates;

use Inc\Base\BaseController;
use inc\Base\User;
use Inc\OB\JsonManagement;
use Inc\Pages\Admin;
use Inc\Utils\Badges;

class GetBadgeTemp extends BaseController {
    const START = 0;
    const ERROR = 1;

    private $json = null;
    private $jsonUrl = null;
    private $badge = null;
    private $field = null;
    private $level = null;

    public static function getInstance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new Self();
        }
        return $inst;
    }

    public function main() {

        $res = $this->loadParm();

        switch ($res) {
            case self::START:
                $this->getStartingPage();
                break;
            case self::ERROR:
                $this->showErrorPage();
                break;
        }

    }


    private function loadParm() {
        if (isset($_GET['json']) && isset($_GET['badge']) && isset($_GET['field']) && isset($_GET['level'])) {
            $badgeId = $_GET['badge'];
            $fieldId = $_GET['field'];
            $levelId = $_GET['level'];
            $this->json = $_GET['json'];
            $this->jsonUrl = JsonManagement::getJsonUrl($this->json);

            $badges = new Badges();
            $this->badge = $badges->getBadgeById($badgeId);
            $this->field = get_term($fieldId, Admin::TAX_FIELDS);
            $this->level = get_term($levelId, Admin::TAX_LEVELS);
            if ($this->badge && $this->field && $this->level && $this->jsonUrl) {
                return self::START;
            } else {
                return self::ERROR;
            }

        } else {
            return false;
        }
    }

    public function getStartingPage() {
        $this->obf_header();
        ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="container inner">
                    <div class="cont-title">New badge</div>
                </div>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <h1 class="badge-title-obf cover-heading">
                        <strong><?php echo $this->badge->post_title; ?></strong>
                    </h1>
                    <h5 class="badge-field">Field: <strong><?php echo $this->field->name; ?></strong> -
                        Level:
                        <strong><?php echo $this->level->name; ?></strong></h5>
                    <p class="lead">
                        <?php echo $this->badge->post_content; ?>
                    </p>
                    <div class="logo-badge-cont">
                        <img src="<?php echo get_the_post_thumbnail_url($this->badge->ID) ?>">
                    </div>
                </div>
            </main>

            <footer class="mastfoot">
                <!--<p><?php echo JsonManagement::getJsonUrl($this->json); ?></p>-->
                <div class="inner">
                    <p class="lead">
                        <a id="gb-continue" class="btn btn-lg btn-secondary" role="button">Continue</a>
                    </p>
                </div>
            </footer>

        </div>

        <?php
        $this->obf_footer();
    }

    public function showTheLoginContent($email) { ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <form method="post" id="gb-form-login" class="gb-form">
                        <h2 class="form-signin-heading">Please sign in</h2>
                        <label for="inputEmail" class="sr-only">Email address / Username</label>
                        <input type="text" readonly class="form-control" id="staticEmail"
                               value="<?php echo $email; ?>">
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                               required="">
                        <div class="checkbox">
                            <label>
                                <input id="inputRemember" type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in
                        </button>
                    </form>
                </div>
            </main>

            <footer class="mastfoot">
                <div class="inner">
                    <div id="gb-resp-login">

                    </div>
                </div>
            </footer>

        </div>

        <?php
    }

    public function showRegisterPage($email) { ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="inner">
                    <div class="cont-title">Registration</div>
                </div>
            </header>

            <main role="main" class="inner cover registration">
                <div class="container">
                    <form id="gb-form-registration" id="needs-validation" novalidate>
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">First name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="reg-first-name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lastName" class="col-sm-4 col-form-label">Last name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="reg-last-name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control" id="reg-email"
                                       value="<?php echo $email; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="reg-user-name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="reg-pass" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputRepeatPassword" class="col-sm-4 col-form-label">Repeat password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="reg-repeat-pass" required>
                            </div>
                        </div>
                        <div class="cont-btn-form-reg">
                            <div id="gb-resp-register">

                            </div>
                            <input type="submit" id="submit-form" style="display: none;"/>
                        </div>
                    </form>
                </div>
            </main>

            <footer class="mastfoot">
                <div class="inner">
                    <label class="btn btn-primary btn-lg" type="submit" for="submit-form"
                           tabindex="0">Register</label>

                </div>
            </footer>

        </div>

        <?php
    }

    public function showOpenBadgesSendIssuer() { ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="inner container">
                    <div class="ob-menu">
                        <span class="ob-cont-title">Mozilla Open Badges</span>
                        <span class="ob-user-info">
                                <?php echo get_avatar(User::getCurrentUser()->ID); ?>
                                <?php echo User::getCurrentUser()->user_login; ?>
                            </span>
                    </div>
                </div>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <div class="jumbotron jumbotron-fluid">
                        <p class="lead">
                            Mozilla Open Badges give you the opportunity to store your badge in its platform to permit
                            to
                            show your progress with all the community.
                            <br><br>
                            If you don’t have an Open Badge account, please click
                            <a href="https://backpack.openbadges.org/backpack/signup" target="_blank" style="font-size: 25px;">here</a>
                            and create a new account with the same email address of the registration of this website and
                            then <strong>get the badge</strong>.

                        </p>
                        <div class="cont-btn-standar">
                            <button id="gb-ob-get-badge" class="btn btn-lg btn-primary" type="submit">Get the badge
                            </button>
                        </div>
                        <div id="gb-ob-response">
                        </div>
                    </div>
                        <button id="gb-get-standard" class="btn btn-link" type="submit">
                            Skip the process
                        </button>
                </div>
            </main>

            <footer class="mastfoot">
                <div class="inner">
                    <div class="logo-open-badges">
                        <img src="<?php echo $this->plugin_url; ?>/assets/images/open-badges-mz-logo.png">
                    </div>
                </div>
            </footer>

        </div>

        <?php
    }


    public function showConclusionPage() {
        ?>

        <div id="gb-wrap" class="cover-container">

            <header class="masthead clearfix">
                <?php echo $this->getInfoHeader(); ?>
                <div class="inner container">
                    <div class="cont-title">Congratulation</div>
                </div>
            </header>

            <main role="main" class="inner cover">
                <div class="container">
                    <h1 class="cong-title-obf cover-heading">
                        <?php echo User::getCurrentUser()->first_name . ", "; ?>you just added a new badge!
                    </h1>
                    <div class="container cont-button-redirect">
                        <div class="row justify-content-around">
                            <div class="col-3">
                                <a class="btn btn-redirect" href="<?php echo get_bloginfo('url'); ?>"
                                   role="button">Home</a>
                            </div>
                        </div>
                    </div>
                    <div class="container">
            </main>

            <footer class="mastfoot">
                <div class="inner">

                </div>
            </footer>
        </div>
        <?php

    }

    private function showErrorPage() {
        $this->obf_header()
        ?>
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div id="gb-wrap" class="cover-container">

                    <header class="masthead clearfix">
                        <div class="info-header-obf">
                            <div class="container">
                                <div><?php echo get_bloginfo('name'); ?></div>
                            </div>
                        </div>
                    </header>

                    <main role="main" class="inner cover">
                        <div class="container">
                            <?php
                            if (!$this->jsonUrl) { ?>
                                <h1>BADGE ERROR</h1>
                                <p class="lead">
                                    Your're badge is not anymore stored in our server.
                                </p>
                                <?php
                            } else {
                                ?>
                                <h1>URL ERROR</h1>
                                <p class="lead">
                                    There's something wrong with the link,<br> ask to the help desk to fix the
                                    problem!
                                </p>
                                <?php
                            }
                            ?>
                        </div>
                    </main>

                    <footer class="mastfoot">
                        <div class="inner">
                            <div id="gb-resp-login"></div>
                        </div>
                    </footer>

                </div>

            </div>
        </div>
        <?php
        $this->obf_footer();
    }

    private function obf_header() {
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Get the Badge</title>
            <script src="https://backpack.openbadges.org/issuer.js"></script>

            <?php wp_head(); ?>
        </head>
        <body>
        <div class="container-wrap">
        <div  class="site-wrapper">
        <div class="site-wrapper-inner">
        <?php
    }

    private function obf_footer() { ?>
        </div>
        </div>
        </div>
        <?php wp_footer(); ?>
        </body>
        </html>

        <?php
    }


    function getInfoHeader() {
        ?>
        <div class="info-header-obf">
            <div class="container">
                <a href="<?php echo get_bloginfo('url'); ?>"><?php echo get_bloginfo('name'); ?></a>
            </div>
        </div>
        <?php
    }
}