<?php

// SEND MAIL FUNCTION

/**
 * Sends a mail to the student in order to give him a link where he can get his badge.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $mail Student's adress mail.
 * @param $badge_name Badge's name.
 * @param $badge_language Badge's language.
 * @param $badge_image Badge's image.
 * @param $url Page's URL where the student can get his badge.
 * @return A boolean to know if the mail has been sent.
*/
function send_mail($mail, $badge_name, $badge_language, $badge_image, $url){
    $subject = "Badges4Languages - You have just earned a badge"; //entering a subject for email

    //Message displayed in the email
    $message= '
    <html>
            <head>
                    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
            </head>
            <body>
                <div id="b4l-award-actions-wrap">
                    <div align="center">
                        <h1>BADGES FOR LANGUAGES</h1>
                        <h2>Learn languages and get official certifications</h2>
                        <hr/>
                        <h1>Congratulations you have just earned a badge!</h1>
                        <h2>'.$badge_name.' - '.$badge_language.'</h2>
                        <a href="'.$url.'">
                            <img src="'.$badge_image.'" width="150" height="150"/>
                        </a>
                        </br>
                        <div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
                        <hr/>
                        <p style="font-size:9px; color:grey">Badges for Languages by My Language Skills, based in Valencia, Spain.
                        More information <a href="https://mylanguageskills.wordpress.com/">here</a>.
                        Legal information <a href="https://mylanguageskillslegal.wordpress.com/category/english/badges-for-languages-english/">here</a>.
                        </p>
                    </div>
                </div>
            </body>
    </html>
    ';

    //Setting headers so it's a MIME mail and a html
    $headers = "From: badges4languages <colomet@hotmail.com>\n";
    $headers .= "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=utf-8"."\n";
    $headers .= "Reply-To: colomet@hotmail.com\n";

    return mail($mail, $subject, $message, $headers); //Sending the emails
}


 ?>
