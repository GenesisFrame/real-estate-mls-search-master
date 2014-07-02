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

<div id="rep-window1" class="rep-window" style="display:none;">
  <div class="wi-bg"></div>
      <div class="wi-wrap wi-request">
          <div class="wi-center">
              <h2 class="wi-line"> CONTACT US </h2>
              <div class="wi-error error-message" style="display:none">
                  <ul><li class="email" style="display:none;">Incorrect email address</li></ul>
              </div>

              <form id="request-showing-form" action="/index.php" method="post">
                <input name="propertyPart" type="hidden" value="requestShowing" /><input name="propertyAction" type="hidden" value="default" />
                Full Name: <input name="firstname" type="text" class="wi-formline-input2 required" value=""/>
                Email: <input name="email" type="text" class="wi-formline-input2 required" value=""/>
                Phone: <input name="phone" type="text" class="wi-formline-input2 required" value=""/>
                Comments: <textarea rows="5" name="comment" class="wi-formline-input2 required"></textarea>
                <div class="wi-formline-inputs wi-btn-line">
                    <input type="submit" value="SUBMIT" class="wi-btn"/>
                </div>
               </form>
          </div>
    <div class="wi-wrap-cross"></div>
  </div>
</div>

<div id="rep-window2" class="rep-window" style="display:none;">
  <div class="wi-bg"></div>
  <div class="wi-wrap">
  	<div class="wi-left">
  	  <h2 class="wi-line">NEW TO <?php echo get_bloginfo('name'); ?>?</h2>

      <div class="wi-error wi-received" style="display:none;">Thanks!</div>

      <form id="de-registration-form" action="<?php echo KenPropertyRouting::generateUrl(array());?>" method="POST">
          <input name="propertyPart" type="hidden" value="registration" />
          
          <div class="wi-notice">Please register so we can provide you with access to full property descriptions, past sales data, and other important information. Register for free below.</div>
          <div class="wi-reg-form">

            <div class="wi-formline clearfix">
                <div class="wi-formline-title">Email</div>
                <div class="wi-formline-inputs">
                    <input name="email" type="text" class="wi-formline-input required" value="" />
                </div>
            </div>
            <div class="wi-formline clearfix">
                <div class="wi-formline-title">Confirm Email</div>
                <div class="wi-formline-inputs">
                    <input name="email-2" type="text" class="wi-formline-input required" value="" />
                </div>
            </div>
            <div class="wi-formline clearfix">
                <div class="wi-formline-title">Password</div>
                <div class="wi-formline-inputs">
                    <input name="password" type="password" class="wi-formline-input required" value="" />
                </div>
            </div>
            <div class="wi-formline clearfix">
                <div class="wi-formline-title">Confirm Password</div>
                <div class="wi-formline-inputs">
                    <input name="password-2" type="password" class="wi-formline-input required" value="" />
                </div>
            </div>
          </div>

          <div class="wi-reg-form">
            <div class="wi-formline clearfix">
                <div class="wi-formline-title"> First Name</div>
                <div class="wi-formline-inputs">
                    <input name="firstname" type="text" class="wi-formline-input required" value="" />
                </div>
            </div>

            <div class="wi-formline clearfix">
                <div class="wi-formline-title"> Last Name</div>
                <div class="wi-formline-inputs">
                    <input name="lastname" type="text" class="wi-formline-input required" value="" />
                </div>
            </div>

            <div class="wi-formline clearfix">
                <div class="wi-formline-title"> Phone</div>
                <div class="wi-formline-inputs">
                    <input name="phone" type="text" class="wi-formline-input required" value="" />
                </div>
            </div>
          </div>

          <div class="wi-reg-form">
            <div class="wi-formline clearfix">
                <div class="wi-formline-title"> Buy/Rent</div>
                <div class="wi-formline-inputs">
                    <select name="buy-rent" class="wi-formline-input">
                        <option>Buy</option>
                        <option>Rent</option>
                    </select>
                </div>
            </div>
            <div class="wi-formline clearfix">
                <div class="wi-formline-title">Price Range</div>
                <div class="wi-formline-inputs">
                    <select name="price-range" class="wi-formline-input">
                        <option>under $50,000</option>
                        <option>$50,000 - $100,000</option>
                        <option>$100,000 - $150,000</option>
                        <option>$150,000 - $200,000</option>
                        <option>$200,000 - $250,000</option>
                        <option>$250,000 - $300,000</option>
                        <option>$300,000 - $350,000</option>
                        <option>$350,000 - $400,000</option>
                        <option>$400,000 - $450,000</option>
                        <option>$450,000 - $500,000</option>
                        <option>$500,000 - $600,000</option>
                        <option>$600,000 - $700,000</option>
                        <option>$700,000 - $800,000</option>
                        <option>$800,000 - $900,000</option>
                        <option>$900,000 - $1,000,000</option>
                        <option>more then $1,000,000</option>
                    </select>
                </div>
            </div>
            <div class="wi-formline clearfix">
                <div class="wi-formline-title">Time Frame</div>
                <div class="wi-formline-inputs">

                    <select name="time-frame" class="wi-formline-input">
                       <option>immediately</option>
                       <option>week</option>
                       <option>month</option>
                       <option>3 months</option>
                    </select>
                </div>
            </div>
          </div>

          <div class="wi-formline-inputs wi-btn-line">
             <input type="submit" value="Register" class="wi-btn" />
          </div>
      </form>

      </div>

      <div class="wi-right">
            <h2 class="wi-line">LOGIN NOW</h2>

            <form id="de-login-form" action="<?php echo KenPropertyRouting::generateUrl(array());?>" method="POST">
                <input name="propertyPart" type="hidden" value="login" />
                
                E-mail: <input name="login-email" type="text" class="wi-formline-input2" value="" />
                Password: <input name="login-password" type="text" class="wi-formline-input2" value="" />

                 <div class="wi-formline-inputs wi-btn-line wi-login-btn">
                    <input type="submit" value="LOGIN" class="wi-btn"/>
                 </div>

                 <h2 class="wi-line"> WHY REGISTER WITH SITE? </h2>

                 <ul class="wi-auth-list">
                   <li class="auth-list-marked">It's free!</li>
                   <li>Stay on top of the real estate market in your areas and neighborhoods</li>
                   <li>Set up showings with an agent!</li>
                   <li>Get email alerts when new listings hit the market</li>
                   <li>Receive/Request our custom research newsletter</li>
                   <li class="auth-list-marked">Get full access to MLS</li>
                 </ul>
            </form>
      </div>

  	<div class="wi-wrap-cross"></div>
  </div>
</div>
</div>