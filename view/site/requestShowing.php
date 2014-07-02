<div class="content-block">
    <div class="content-block-wrapper">
        <div class="side">
            <div class="side-body">
                <div class="side-item">
                    <?php if ( isset($this->result['result']['errors']) && empty($this->result['result']['errors']) ): ?>
                        <?php if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'])): ?>
                            <meta http-equiv="refresh" content="0;url=<?php echo $_SERVER['HTTP_REFERER']; ?>">
                        <?php else: ?>
                            <meta http-equiv="refresh" content="0;url=<?php echo  home_url('/')?>">
                        <?php endif; ?>
                        
                        <h1>Thanks for sending your message!</h1>
                        <br/>
                        <p>We'll get back to you shortly.</p>
                    <?php else: ?>
                        <h1>Service temporary unavailable!</h1>
                        <br/>
                        <p>Please try again later..</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>