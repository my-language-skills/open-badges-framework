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
    const OB_CONF = 1;
    const OB_DENY = 2;
    const ERROR = 3;

    public $json = null;
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
            case self::OB_CONF:
                $this->getOpenBadgesPage(false);
                break;
            case self::OB_DENY:
                $this->getOpenBadgesPage(true);
                break;
            case self::ERROR:
                $this->getErrorPage();
                break;
        }

    }


    private function loadParm() {
        if (isset($_GET['json']) && isset($_GET['badge']) && isset($_GET['field']) && isset($_GET['level'])) {
            $badgeId = $_GET['badge'];
            $fieldId = $_GET['field'];
            $levelId = $_GET['level'];

            $this->json = $_GET['json'];
            $badges = new Badges();
            $this->badge = $badges->getBadgeById($badgeId);
            $this->field = get_term($fieldId, Admin::TAX_FIELDS);
            $this->level = get_term($levelId, Admin::TAX_LEVELS);

            if (!($this->badge && $this->field && $this->level)) {
                return self::ERROR;
            } else if ($this->checkOpenBadgesParam()) {
                return self::OB_CONF;
            } else if ($this->checkOpenBadgesDeny()) {
                return self::OB_DENY;
            } else if ($this->badge && $this->field && $this->level) {
                return self::START;
            } else {
                return self::ERROR;
            }


        } else {
            return false;
        }
    }

    private function checkOpenBadgesParam() {
        if (isset($_GET['access_token']) && isset($_GET['refresh_token']) && isset($_GET['expires']) &&
            isset($_GET['api_root'])) {
            return true;
        } else {
            return false;
        }
    }

    private function checkOpenBadgesDeny() {
        if (isset($_REQUEST['error'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getStartingPage() {
        $this->obf_header()

        ?>
        <div id="gb-wrap" class="site-wrapper">

            <div id="wrap-the-badge" class="site-wrapper-inner">

                <div class="cover-container">

                    <header class="masthead clearfix">
                        <div class="inner">
                            <div class="cont-title">New badge</div>
                        </div>
                    </header>

                    <main role="main" class="inner cover">
                        <h1 class="badge-title-obf cover-heading">
                            <strong><?php echo $this->badge->post_title; ?></strong>
                        </h1>
                        <h5 class="badge-field">Field: <strong><?php echo $this->field->name; ?></strong> - Level:
                            <strong><?php echo $this->level->name; ?></strong></h5>
                        <p class="lead">
                            <?php echo $this->badge->post_content; ?>
                        </p>
                        <div class="logo-badge-cont">
                            <img src="<?php echo get_the_post_thumbnail_url($this->badge->ID) ?>">
                        </div>
                    </main>

                    <footer class="mastfoot">
                        <!--<p><?php echo JsonManagement::getJsonUrl($this->json); ?></p>-->
                        <div class="inner">
                            <p class="lead">
                                <a id="getBadge" class="btn btn-lg btn-secondary" role="button">Get the badge</a>
                            </p>
                        </div>
                    </footer>

                </div>

            </div>

        </div>

        <?php
        $this->obf_footer();
    }

    public function showTheLoginContent($email) { ?>

        <div id="wrap-login" class="site-wrapper-inner">

            <div class="cover-container">

                <header class="masthead clearfix">

                </header>

                <main role="main" class="inner cover">
                    <form method="post" id="gb-form-login">
                        <h2 class="form-signin-heading">Please sign in</h2>
                        <label for="inputEmail" class="sr-only">Email address / Username</label>
                        <input type="text" readonly class="form-control-plaintext" id="staticEmail"
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

                </main>

                <footer class="mastfoot">
                    <div class="inner">
                        <div id="gb-resp-login"></div>
                    </div>
                </footer>

            </div>

        </div>

        <?php
    }

    public function showOpenBadgesLoginContent($email) { ?>

        <div id="wrap-login" class="site-wrapper-inner">

            <div class="cover-container">

                <header class="masthead clearfix">
                    <div class="inner">
                        <div class="ob-menu">
                            <span class="ob-cont-title">Open Badges identification</span>
                            <span class="ob-user-info">
                                <?php echo get_avatar(User::getCurrentUser()->ID); ?>
                                <?php echo User::getCurrentUser()->user_login; ?>
                            </span>
                        </div>
                    </div>
                </header>

                <main role="main" class="inner cover">
                    <p class="lead">To receive the badge we need to validate your open badge account, that is the place
                        were all your badge are stored and showed to all the community.
                        <br><br>
                        If you don’t have an Open Badge account, please click the below link and create a new account
                        with the same email address of the registration of this website.
                        <br><a href="https://backpack.openbadges.org/backpack/signup">https://backpack.openbadges.org/backpack/signup</a>
                    </p>
                    <form method="post" id="gb-form-open-badges-login">
                        <label for="inputEmail" class="sr-only">Email address / Username</label>
                        <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                               value="<?php echo $email; ?>">

                        <button class="btn btn-lg btn-primary btn-block" type="submit">Confirm the email</button>
                    </form>


                </main>

                <footer class="mastfoot">
                    <div class="inner">
                        <div class="logo-open-badges">
                            <img src="<?php echo $this->plugin_url; ?>/assets/images/open-badges-mz-logo.png">
                        </div>
                    </div>
                </footer>

            </div>

        </div>

        <?php

    }

    private function getOpenBadgesPage($error = false) {
        $this->obf_header()

        ?>
        <div id="gb-wrap" class="site-wrapper">
            <div id="wrap-login" class="site-wrapper-inner">

                <div class="cover-container">

                    <header class="masthead clearfix">
                    </header>

                    <main role="main" class="inner cover">
                        <?php
                        if ($error) {
                            // ##### ERROR SECTION
                            ?>

                            <h1>Access denied</h1>
                            <p class="lead">Restart the process to get the badge </p>
                            <a class="btn btn-lg btn-secondary" href="<?php
                            $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                            $baseUrl = substr($baseUrl, 0, strrpos($baseUrl, '/') + 1);

                            $baseUrl .= Admin::SLUG_GETBADGE .
                                "/?json=" . $this->json .
                                "&badge=" . $this->badge->ID .
                                "&field=" . $this->field->term_id .
                                "&level=" . $this->level->term_id;
                            echo $baseUrl;
                            ?>">Restart</a>
                            <?php
                        } else {
                            // ##### RIGHT SECTION
                            ?>
                            <h1>Just the last step</h1>
                            <br>
                            <button id="gb-button" class="btn btn-lg btn-primary">GET THE BADGE</button>

                            <?php
                        }
                        ?>
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

    private function getErrorPage() {
        $this->obf_header()
        ?>
        <div id="gb-wrap" class="site-wrapper">
            <div id="wrap-login" class="site-wrapper-inner">

                <div class="cover-container">

                    <header class="masthead clearfix">
                    </header>

                    <main role="main" class="inner cover">
                        <h1>URL ERROR <?php echo User::getCurrentUser()->user_login; ?></h1>
                        <p class="lead">There's something wrong with the link,<br> ask to the help desk to fix the
                            problem!</p>
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
            <title>My WordPress Plugin Front-end Page</title>
            <script src="https://backpack.openbadges.org/issuer.js"></script>

            <?php wp_head(); ?>
        </head>
        <body>
        <?php
    }

    private function obf_footer() { ?>
        <?php wp_footer(); ?>
        </body>
        </html>

        <?php
    }

}