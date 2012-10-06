<?php

# WordPress on SMS v1.0
# Description: Publish posts on WordPress.com blogs through SMS
# Developed for 55444.in Platform
# Registered at http://55444.in/apps/wordpress
# Author: Arjun Abhynav
# Contact: arjun.abhynav@gmail.com
# Website: http://arjunabhynav.com
# Twitter: @arjunabhynav

header('Content-Type: text/xml');

require_once('config/database.php');
include('classes/User.php');
include('classes/BlogPost.php');
include('classes/Request.php');

?>
<response>
    <content>
    <?php
    $variables = array(
    'mobile' => $_GET['mobile'],
    'message' => $_GET['message']);
    $request = new Request($variables);
    $response = $request->process();
    echo $response;
    ?>
    </content>
</response>
