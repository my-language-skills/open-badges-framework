<?php ?>
///**
// * Created by PhpStorm.
// * User: aleric
// * Date: 10/11/2017
// * Time: 12:21
// */
//
//namespace templates;
//
//
//class GetBadgeTemp {
//
//    public function __construct() {
//        add_shortcode('get_badge', array($this, 'showGetBadge'));
//    }
//
//    public function showGetBadge() {
//
//    }
//
//}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My WordPress Plugin Front-end Page</title>
<?php
//call the wp head so  you can get most of your wordpress
wp_head();
?>
</head>
<body>
<h1>Here's my plugin front-end page</h1>
<h2>You can put anything you want in this php file.</h2>
<?php
//call the wp foooter
wp_footer();
?>
</body>
</html>