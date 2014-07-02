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

 
class WidgetSearchForm extends WP_Widget {

    public function WidgetSearchForm()
    {
        $widget_ops = array('classname' => 'KenWidgetSearchForm', 'description' => 'Search Property Form');
	    $control_ops = array(); //array('width' => 400, 'height' => 200);

	    $this->WP_Widget('KenWidgetSearchProperty', 'Search Property', $widget_ops, $control_ops);
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_name'])) {
            $args['widget_name'] = 'Search Property';
        }

        $args['before_widget'] = trim($args['before_widget']);

        echo $args['before_widget'];
        echo $args['before_title'] . $args['widget_name'] . $args['after_title'];

        $propertySearch = KenRequest::query('propertySearch');
        $options = KenProperty::getInstance()->getOptions();

        $searchTypes = isset($propertySearch['type']) ? $propertySearch['type'] : array();
        $existsTypes = array();
        
        foreach ($searchTypes as $value) {
            $existsTypes[] = $value;
        }

        if (empty($existsTypes)) {
            $existsTypes = array('CC', 'SF' , 'MF', 'LD');
        }

        function getSearchValue($value)
        {
            global $propertySearch;

            if ( isset($propertySearch[$value]) && !empty($propertySearch[$value])) {
                return strip_tags($propertySearch[$value]);
            }

            return '';
        }

        $city = $options['defaultCity'];
        $area = $options['defaultArea'];

        if (getSearchValue('city') != '') {
            $city = getSearchValue('city');
        }

        if ( (
              getSearchValue('mls') != '' || getSearchValue('zip-code') != '' ||  getSearchValue('neighbourhood') != '' ||
              (isset($propertySearch['open']) &&  $propertySearch['open'] == "on")
             )
           ) {
            $city = '';
        }
        
         
        if (getSearchValue('neighbourhood') != '') {
            $area = getSearchValue('neighbourhood');
        }

        function generatePriceInput($fieldName)
        {
            $out = '<select class="re-formline-input re-50pr-l" name="propertySearch['.$fieldName.']"><option value="">Any</option>';
            for ($i=300000; $i<=5000000; $i=$i+50000) {
                $out .= '<option value="'.$i.'" ' .  (getSearchValue($fieldName) == $i ? "selected=selected" : "") . '>'. number_format($i).'</option>';
            }
            $out .= '</select>';
            return $out;
        }

        $out = '<div id="rep-widget" class="ken-search search-filters re-plugin">
                    <form method="get" action="' . KenPropertyRouting::generateUrl(array()) . '">
                        <input type="hidden" name="propertyAction" value="default" />
                        <input type="hidden" name="propertySearch[neighbourhood-id]" value="" />

                        <div class="re-formline clearfix">
                            <div class="re-formline-title">MLS#:</div>
                            <div class="re-formline-inputs">
                                <input name="propertySearch[mls]" type="text" class="re-formline-input" value="' . getSearchValue('mls') . '"/>
                            </div>
                        </div>

                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> City:</div>
                            <div class="re-formline-inputs">
                                <input name="propertySearch[city]" type="text" class="re-formline-input search-city-name" value="' . $city . '"/>
                            </div>
                        </div>

                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> Zip Code:</div>
                            <div class="re-formline-inputs">
                                <input name="propertySearch[zip-code]" type="text" class="re-formline-input" value="' . getSearchValue('zip-code') . '"/>
                            </div>
                        </div>

                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> Area:</div>
                            <div class="re-formline-inputs">
                                <input name="propertySearch[neighbourhood]" type="text" class="re-formline-input" value="' . $area . '"/>
                            </div>
                        </div>

                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> Price:</div>
                            <div class="re-formline-inputs">
                                '. generatePriceInput('price-from') .'
                                <div class="re-50pr-c">&#8212;</div>
                                '. generatePriceInput('price-to') .'
                            </div>
                        </div>

                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> Beds:</div>
                            <div class="re-formline-inputs">
                                <select name="propertySearch[beds-from]" class="re-formline-input re-50pr-l">
                                    <option value="">Any</option>
                                    <option value="1" ' .  (getSearchValue('beds-from') == 1 ? "selected=selected" : "") . '>1</option>
                                    <option value="2" ' .  (getSearchValue('beds-from') == 2 ? "selected=selected" : "") . '>2</option>
                                    <option value="3" ' .  (getSearchValue('beds-from') == 3 ? "selected=selected" : "") . '>3</option>
                                    <option value="4" ' .  (getSearchValue('beds-from') == 4 ? "selected=selected" : "") . '>4</option>
                                    <option value="5" ' .  (getSearchValue('beds-from') == 5 ? "selected=selected" : "") . '>5</option>
                                    <option value="6" ' .  (getSearchValue('beds-from') == 6 ? "selected=selected" : "") . '>6</option>
                                    <option value="7" ' .  (getSearchValue('beds-from') == 7 ? "selected=selected" : "") . '>7</option>
                                    <option value="8" ' .  (getSearchValue('beds-from') == 8 ? "selected=selected" : "") . '>8+</option>
                                </select>
                                <div class="re-50pr-c">&#8212;</div>
                                <select name="propertySearch[beds-to]" class="re-formline-input re-50pr-r">
                                    <option value="">Any</option>
                                    <option value="1" ' .  (getSearchValue('beds-to') == 1 ? "selected=selected" : "") . '>1</option>
                                    <option value="2" ' .  (getSearchValue('beds-to') == 2 ? "selected=selected" : "") . '>2</option>
                                    <option value="3" ' .  (getSearchValue('beds-to') == 3 ? "selected=selected" : "") . '>3</option>
                                    <option value="4" ' .  (getSearchValue('beds-to') == 4 ? "selected=selected" : "") . '>4</option>
                                    <option value="5" ' .  (getSearchValue('beds-to') == 5 ? "selected=selected" : "") . '>5</option>
                                    <option value="6" ' .  (getSearchValue('beds-to') == 6 ? "selected=selected" : "") . '>6</option>
                                    <option value="7" ' .  (getSearchValue('beds-to') == 7 ? "selected=selected" : "") . '>7</option>
                                    <option value="8" ' .  (getSearchValue('beds-to') == 8 ? "selected=selected" : "") . '>8+</option>
                                </select>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> Baths:</div>
                            <div class="re-formline-inputs">
                                <select name="propertySearch[bath-from]" class="re-formline-input re-50pr-l">
                                    <option value="">Any</option>
                                    <option value="1" ' .  (getSearchValue('bath-from') == 1 ? "selected=selected" : "") . '>1</option>
                                    <option value="2" ' .  (getSearchValue('bath-from') == 2 ? "selected=selected" : "") . '>2</option>
                                    <option value="3" ' .  (getSearchValue('bath-from') == 3 ? "selected=selected" : "") . '>3</option>
                                    <option value="4" ' .  (getSearchValue('bath-from') == 4 ? "selected=selected" : "") . '>4</option>
                                    <option value="5" ' .  (getSearchValue('bath-from') == 5 ? "selected=selected" : "") . '>5</option>
                                    <option value="6" ' .  (getSearchValue('bath-from') == 6 ? "selected=selected" : "") . '>6</option>
                                    <option value="7" ' .  (getSearchValue('bath-from') == 7 ? "selected=selected" : "") . '>7</option>
                                    <option value="8" ' .  (getSearchValue('bath-from') == 8 ? "selected=selected" : "") . '>8+</option>
                                </select>
                                <div class="re-50pr-c">&#8212;</div>
                                <select name="propertySearch[bath-to]" class="re-formline-input re-50pr-r">
                                    <option value="">Any</option>
                                    <option value="1" ' .  (getSearchValue('bath-to') == 1 ? "selected=selected" : "") . '>1</option>
                                    <option value="2" ' .  (getSearchValue('bath-to') == 2 ? "selected=selected" : "") . '>2</option>
                                    <option value="3" ' .  (getSearchValue('bath-to') == 3 ? "selected=selected" : "") . '>3</option>
                                    <option value="4" ' .  (getSearchValue('bath-to') == 4 ? "selected=selected" : "") . '>4</option>
                                    <option value="5" ' .  (getSearchValue('bath-to') == 5 ? "selected=selected" : "") . '>5</option>
                                    <option value="6" ' .  (getSearchValue('bath-to') == 6 ? "selected=selected" : "") . '>6</option>
                                    <option value="7" ' .  (getSearchValue('bath-to') == 7 ? "selected=selected" : "") . '>7</option>
                                    <option value="8" ' .  (getSearchValue('bath-to') == 8 ? "selected=selected" : "") . '>8+</option>
                                </select>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> SqFt:</div>
                            <div class="re-formline-inputs">
                                <select name="propertySearch[sqft-from]" class="re-formline-input re-50pr-l">
                                    <option value="">Any</option>
                                    <option value="100" ' .  (getSearchValue('sqft-from') == 100 ? "selected=selected" : "") . '>100</option>
                                    <option value="500" ' .  (getSearchValue('sqft-from') == 500 ? "selected=selected" : "") . '>500</option>
                                    <option value="1000" ' .  (getSearchValue('sqft-from') == 1000 ? "selected=selected" : "") . '>1000</option>
                                    <option value="2000" ' .  (getSearchValue('sqft-from') == 2000 ? "selected=selected" : "") . '>2000</option>
                                    <option value="3000" ' .  (getSearchValue('sqft-from') == 3000 ? "selected=selected" : "") . '>3000</option>
                                    <option value="4000" ' .  (getSearchValue('sqft-from') == 4000 ? "selected=selected" : "") . '>4000</option>
                                    <option value="5000" ' .  (getSearchValue('sqft-from') == 5000 ? "selected=selected" : "") . '>5000</option>
                                    <option value="10000" ' .  (getSearchValue('sqft-from') == 10000 ? "selected=selected" : "") . '>10000</option>
                                    <option value="15000" ' .  (getSearchValue('sqft-from') == 15000 ? "selected=selected" : "") . '>15000</option>
                                    <option value="20000" ' .  (getSearchValue('sqft-from') == 20000 ? "selected=selected" : "") . '>20000</option>
                                    <option value="30000" ' .  (getSearchValue('sqft-from') == 30000 ? "selected=selected" : "") . '>30000</option>
                                    <option value="40000" ' .  (getSearchValue('sqft-from') == 40000 ? "selected=selected" : "") . '>40000</option>
                                    <option value="50000" ' .  (getSearchValue('sqft-from') == 50000 ? "selected=selected" : "") . '>50000</option>
                                </select>
                                <div class="re-50pr-c">&#8212;</div>
                                <select name="propertySearch[sqft-to]" class="re-formline-input re-50pr-r">
                                    <option value="">Any</option>
                                    <option value="100" ' .  (getSearchValue('sqft-to') == 100 ? "selected=selected" : "") . '>100</option>
                                    <option value="500" ' .  (getSearchValue('sqft-to') == 500 ? "selected=selected" : "") . '>500</option>
                                    <option value="1000" ' .  (getSearchValue('sqft-to') == 1000 ? "selected=selected" : "") . '>1000</option>
                                    <option value="2000" ' .  (getSearchValue('sqft-to') == 2000 ? "selected=selected" : "") . '>2000</option>
                                    <option value="3000" ' .  (getSearchValue('sqft-to') == 3000 ? "selected=selected" : "") . '>3000</option>
                                    <option value="4000" ' .  (getSearchValue('sqft-to') == 4000 ? "selected=selected" : "") . '>4000</option>
                                    <option value="5000" ' .  (getSearchValue('sqft-to') == 5000 ? "selected=selected" : "") . '>5000</option>
                                    <option value="10000" ' .  (getSearchValue('sqft-to') == 10000 ? "selected=selected" : "") . '>10000</option>
                                    <option value="15000" ' .  (getSearchValue('sqft-to') == 15000 ? "selected=selected" : "") . '>15000</option>
                                    <option value="20000" ' .  (getSearchValue('sqft-to') == 20000 ? "selected=selected" : "") . '>20000</option>
                                    <option value="30000" ' .  (getSearchValue('sqft-to') == 30000 ? "selected=selected" : "") . '>30000</option>
                                    <option value="40000" ' .  (getSearchValue('sqft-to') == 40000 ? "selected=selected" : "") . '>40000</option>
                                    <option value="50000" ' .  (getSearchValue('sqft-to') == 50000 ? "selected=selected" : "") . '>50000</option>
                                </select>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-title">Year:</div>
                            <div class="re-formline-inputs">
                                <input name="propertySearch[year-from]" type="text" class="re-formline-input  re-50pr-l" value="' . getSearchValue('year-from') . '"/>
                                <div class="re-50pr-c">&#8212;</div>
                                <input name="propertySearch[year-to]" type="text" class="re-formline-input  re-50pr-r" value="' . getSearchValue('year-to') . '"/>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-title"> Type:</div>
                            <div class="re-formline-inputs">
                                <select name="propertySearch[type][]" size="4" multiple class="re-formline-input">
                                    <option value="SF" ' .  (in_array('SF', $existsTypes) ? "selected=selected" : "") . '>Single Family</option>
                                    <option value="MF" ' .  (in_array('MF', $existsTypes) ? "selected=selected" : "") . '>Multi-family</option>
                                    <option value="CC" ' .  (in_array('CC', $existsTypes) ? "selected=selected" : "") . '>Condo</option>
                                    <option value="LD" ' .  (in_array('LD', $existsTypes) ? "selected=selected" : "") . '>Land</option>
                                </select>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-title">Status:</div>
                            <div class="re-formline-inputs">
                                <select size="3" multiple class="re-formline-input">
                                    <option value="AC" selected>Active</option>
                                    <option value="SO">Sold</option>
                                    <option value="OF">Off market</option>
                                </select>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-inputs">
                              <label><input name="propertySearch[open]" type="checkbox" value="" ' . (isset($propertySearch['open']) &&  $propertySearch['open'] == "on" ? "checked=checked" : "") . ' /> Open Houses Only</label>
                            </div>
                        </div>
                        <div class="re-formline clearfix">
                            <div class="re-formline-inputs">
                              <label><input name="propertySearch[status]" value="NEW" type="checkbox" value="" ' . (isset($propertySearch['status']) &&  $propertySearch['status'] == "NEW" ? "checked=checked" : "") . ' /> </label>
                              New Listings Only</div>
                        </div>
                        <div class="re-formline re-btn-line clearfix">
                            <div class="re-formline-inputs">
                              <input name="Reset" type="reset" class="re-btn" value="Clear"/>
                              <input type="submit" value="Search" class="re-btn"/>
                            </div>
                        </div>
                        
                    </form>
                </div>';

        echo $out;
        echo $args['after_widget'];
    }

    /*function update($new_instance, $old_instance)
    {

    }

    function form($instance)
    {

    }*/

}
