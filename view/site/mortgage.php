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
        <div class="side single">
            <div class="side-body">
        <h1>Mortgage Payment Calculator</h1>
<form action="<?php echo KenPropertyRouting::generateUrl(array()) ?>" method="post" >
    <input type="hidden" name="propertyPart" value="mortgage" />
    
    <table class="search-filters" width="60%" style="margin:10px auto;">
      <tr>
          <td>Loan balance:</td>
          <td style="text-align:left; padding-left: 10px;">
              <input class="wrg-text search-text-long" name="balance" type="text" size="15" value="<?php echo $this->balance; ?>" /> $
          </td>
      </tr>
      <tr>
          <td>Downpayment:</td>
          <td style="text-align:left; padding-left: 10px;">
              <input class="wrg-text search-text-long" name="downpayment" type="text" size="15" value="<?php echo $this->downpayment; ?>" /> $
          </td>
      </tr>
      <tr>
          <td>Interest rate:</td>
          <td style="text-align:left; padding-left: 10px;">
              <input class="wrg-text search-text-long" name="rate" type="text" size="5" value="<?php echo $this->rate; ?>" /> %
          </td>
      </tr>
      <tr>
          <td>Loan term:</td>
          <td style="text-align:left; padding-left: 10px;">
              <input class="wrg-text search-text-long" name="term" type="text" size="5" value="<?php echo $this->term; ?>" /> years
          </td>
      </tr>
      <tr>
          <td style="text-align:center;" colspan="2">
              <br/>
              <input class="text content-link-small" type="submit" name="submitBtn" value="Calculate" />
          </td>
      </tr>
    </table>  
</form>

<?php if ($this->submitted == true) : ?>
<h2>RESULT:</h2>
    <div id="result">
        <table width="60%" style="margin:0 auto;">
            <tr>
                <td>Monthly payment:</td><td class='res'> $<?php echo number_format($this->pay) ?></td>
            </tr>
            <tr>
                <td>Total interest:</td><td> $<?php echo number_format(($this->term*$this->pay*$this->period)-$this->balanceDownpayment) ?></td>
            </tr>
        </table>
    </div>

<br/><br/>
<table class="detail" width="60%" style="margin:0 auto;">
 <tr>
    <td>Year</td>
    <td>Principial</td>
    <td>Interest</td>
    <td>Payment</td>
 </tr>
    <?php
     $balance = $this->balanceDownpayment;
     for ($i=0; $i < ($this->term * $this->period); $i++) {
    
      $tmp = (($this->pay) - ($balance*($this->rate/100/$this->period)));
      $diff = round($tmp, 2);
      $int  = round(($balance * $this->rate/100/$this->period),2);
      $princ = $balance - $diff;
      $balance = round($balance, 0);

      echo "
      <tr>
        <td>$i month</td>
        <td> $".number_format($balance)."</td>
        <td> $".number_format($int)."</td>
        <td> $".number_format($this->pay)."</td>
      </tr>";
      $balance = $princ;
      }
    ?>
 </table>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>
