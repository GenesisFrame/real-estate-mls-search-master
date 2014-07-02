<?php
/*
Plugin Name: Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/
?>

<div class="wrap">
<h2>Documentation</h2>

<p><strong>Embeded Search Results </strong><br>
Embed search results on your website without asking users for any input.</p>
<table width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="11%" valign="top"><strong>Format:</strong></td>
    <td width="89%">&nbsp;</td>
  </tr>
  <tr valign="top">
    <td colspan="2"><p>List search terms within square brackets separated by spaces:<br>
    [recloud-search price_from=&quot;100000&quot; price_to=&quot;400000&quot; orderby=&quot;price-low&quot;<br>
        <br>
    </p></td>
  </tr>
  <tr>
    <td valign="top"><strong>orderby &nbsp; &nbsp; &nbsp; </strong></td>
    <td><p> Sorting Oprions:<br>
      &quot;price-high&quot; - Highest first<br>
      &quot;price-low&quot;&nbsp; - Lowest first<br>
      &quot;new&quot;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; - Newest first</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>type</strong></td>
    <td><p>Property types, can be separated by commas: <br>
    type=&quot;CC,SF,MF,LD&quot;</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>zip_code&nbsp; &nbsp; &nbsp; </strong></td>
    <td><p>Limit the search to the zipcode, can be separated by commas.<br>
      zip_code=&quot;02210,02211&quot;
      <br>
        <strong>NOTE:</strong> If you use zip_code shotccode, <strong>city</strong> and <strong>mls values</strong> will be disregarded</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>mls</strong></td>
    <td><p>Shows  mls listings based on MLS numbers<br>
mls=&quot;1234567,7654321&quot;</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>neighbourhood</strong></td>
    <td><p>List preperties in chosen  neighbourhoods<br>
        neighbourhood=&quot;downtown, south end&quot;<br>
  NOTE: if specified <strong>city</strong>, <strong>zip-code</strong> &amp; <strong>mls</strong> will be ignored</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>status</strong></td>
    <td><p>Search based on listing status. <br>
      status = &quot;&quot;ACT,NEW&quot;</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>price_from</strong></td>
    <td>Search by low-end price<br>
      price_from=&quot;100000&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>price_to</strong></td>
    <td>Search by high-end price<br>
price_to=&quot;1000000&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>beds_from &nbsp; &nbsp; </strong></td>
    <td>Search by the minimum number of bedrooms<br>
    beds_from=&quot;1&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>beds_to</strong></td>
    <td>Search by the maximum number of bedrooms<br>
      beds_to=&quot;5&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>baths_from</strong></td>
    <td>Search by the maximum number of bathrooms<br>
    baths_from=&quot;1&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>baths_to</strong></td>
    <td>Search by the maximum number of bathcrooms<br>
    baths_to=&quot;5&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>year_from &nbsp; &nbsp; </strong></td>
    <td>Search by the earliest year built<br>
    year_from=&quot;1991&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>year_to</strong></td>
    <td>Search by the latest year built<br>
    <strong>year_from</strong>=&quot;2011&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>sqft_from</strong></td>
    <td>Search by the mimnimum property square footage<br>
    <strong>sqft_from</strong>=&quot;1000&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>sqft_to</strong></td>
    <td>Search by the maximum property square footage<br>
    <strong>sqft_from</strong>=&quot;5000&quot;</td>
  </tr>
  <tr>
    <td valign="top"><strong>open&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </strong></td>
    <td>Show open houses only</td>
  </tr>
</table>
<p>&nbsp;</p>

<p><strong>Address-based search</strong><br>
  Allows you to pull all the listings located at the selected address, this may be usefull on your buildings pages.
</p>
<table width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="11%" valign="top"><strong>Format:</strong></td>
    <td width="89%">&nbsp;</td>
  </tr>
  <tr valign="top">
    <td colspan="2"><p>List search terms within square brackets separated by spaces:<br>
    [recloud-forsale address=&quot;180 Beacon St.&quot; city=&quot;Boston&quot; status=&quot;ACT&quot;]<br>
        <br>
    </p></td>
  </tr>
  <tr>
    <td valign="top"><strong>address &nbsp; &nbsp; &nbsp; </strong></td>
    <td><p> Address line can include both:<br>
      Street number and street name
    such as: 123 Main Street</p></td>
  </tr>
  <tr>
    <td valign="top"><strong>city</strong></td>
    <td>City Name</td>
  </tr>
  <tr>
    <td valign="top"><strong>status</strong></td>
    <td><p>MLS listing status (comma separated): <br />
      status = &quot;ACT,NEW,SLD,EXP, RNT&quot;<br />
      <strong>WARNING: </strong>Even though this function will allow you to display sold listings without having the user to login, <strong>you are still  liable to hide the images</strong> as per MLS regulations in most areas. Please refer to compliance. <br />
    To avoid any trouble <strong>DO NOT</strong> include <em>SLD</em> listings types, and simply use <strong>SOLD DATA</strong> short-code for sold listings.</p>
      <p><br />
    </p></td>
  </tr>
</table>
<p>&nbsp;</p>
</div>
