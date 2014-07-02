<?php
/*
Plugin Name: Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5.1
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/
?>

<div class="content-block">
    <div class="content-block-wrapper">
        <div class="side">
            <div class="side-body">
                <div class="side-item">
                    <?php if ( isset($this->userNotFound) ): ?>
                        <h1>User was not found!</h1>
                        <br/>
                        <p>Your password or login is incorrect.</p>
                    <?php else: ?>
                        <h1>You are logged in!</h1>
                    
                        <?php if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'])): ?>
                            <meta http-equiv="refresh" content="0;url=<?php echo $_SERVER['HTTP_REFERER']; ?>">
                        <?php else: ?>
                            <meta http-equiv="refresh" content="0;url=<?php echo  home_url('/')?>">
                        <?php endif; ?>

                        <br/>
                        <p><a href="/">Homepage</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
