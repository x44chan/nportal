/**
 * FILE: jQuery.ptTileSelect.js
 *  
 * @fileOverview
 * jQuery plugin for displaying a popup that allows a user
 * to define a time and set that time back to a form's input
 * field.
 *  
 * @version 0.8
 * @author  Paul Tavares, www.purtuga.com
 * @see     http://ptTimeSelect2.sourceforge.net
 * 
 * @requires jQuery {@link http://www.jquery.com}
 * 
 * 
 * LICENSE:
 * 
 *  Copyright (c) 2007 Paul T. (purtuga.com)
 *  Dual licensed under the:
 *
 *  -   MIT
 *      <http://www.opensource.org/licenses/mit-license.php>
 * 
 *  -   GPL
 *      <http://www.opensource.org/licenses/gpl-license.php>
 *  
 *  User can pick whichever one applies best for their project
 *  and doesn not have to contact me.
 * 
 * 
 * INSTALLATION:
 * 
 * There are two files (.css and .js) delivered with this plugin and
 * that must be included in your html page after the jquery.js library
 * and the jQuery UI style sheet (the jQuery UI javascript library is
 * not necessary).
 * Both of these are to be included inside of the 'head' element of
 * the document. Example below demonstrates this along side the jQuery
 * libraries.
 * 
 * |    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
 * |    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.22/themes/redmond/jquery-ui.css" />
 * |
 * |    <link rel="stylesheet" type="text/css" href="jquery.ptTimeSelect2.css" />
 * |    <script type="text/javascript" src="jquery.ptTimeSelect2.js"></script>
 * |
 * 
 * USAGE:
 * 
 *     -    See <$(ele).ptTimeSelect2()>
 * 
 * 
 * 
 * LAST UPDATED:
 * 
 *         - $Date: 2012/08/05 19:40:21 $
 *         - $Author: paulinho4u $
 *         - $Revision: 1.8 $
 * 
 */

(function($){
    
    /**
     *  jQuery definition
     *
     *  @see    http://jquery.com/
     *  @name   jQuery
     *  @class  jQuery Library
     */
    
    /**
     * jQuery 'fn' definition to anchor all public plugin methods.
     * 
     * @see         http://jquery.com/
     * @name        fn
     * @class       jQuery Library public method anchor
     * @memberOf    jQuery
     */
    
    /**
     *  Namespace for all properties and methods
     *  
     *  @namespace   ptTimeSelect2
     *  @memberOf    jQuery
     */
    jQuery.ptTimeSelect2         = {};
    jQuery.ptTimeSelect2.version = "__BUILD_VERSION_NUMBER__";
    
    /**
     * The default options for all calls to ptTimeSelect2. Can be
     * overwriten with each individual call to {@link jQuery.fn.ptTimeSelect2}
     *  
     * @type {Object} options
     * @memberOf jQuery.ptTimeSelect2
     * @see jQuery.fn.ptTimeSelect2
     */
    jQuery.ptTimeSelect2.options = {
        containerClass: undefined,
        containerWidth: '22em',
        hoursLabel:     'Hour',
        minutesLabel:   'Minutes',
        setButtonLabel: 'Set',
        popupImage:     undefined,
        onFocusDisplay: true,
        zIndex:         10,
        onBeforeShow:   undefined,
        onClose:        undefined
    };
    
    /**
     * Internal method. Called when page is initialized to add the time
     * selection area to the DOM.
     *  
     * @private
     * @memberOf jQuery.ptTimeSelect2
     * @return {undefined}
     */
    jQuery.ptTimeSelect2._ptTimeSelect2Init = function () {
        jQuery(document).ready(
            function () {
                //if the html is not yet created in the document, then do it now
                if (!jQuery('#ptTimeSelect2Cntr').length) {
                    jQuery("body").append(
                            '<div id="ptTimeSelect2Cntr" class="">'
                        +    '        <div class="ui-widget ui-widget-content ui-corner-all">'
                        +    '        <div class="ui-widget-header ui-corner-all">'
                        +    '            <div id="ptTimeSelect2CloseCntr" style="float: right;">'
                        +    '                <a href="javascript: void(0);" onclick="jQuery.ptTimeSelect2.closeCntr();" '
                        +    '                        onmouseover="jQuery(this).removeClass(\'ui-state-default\').addClass(\'ui-state-hover\');" '
                        +    '                        onmouseout="jQuery(this).removeClass(\'ui-state-hover\').addClass(\'ui-state-default\');"'
                        +    '                        class="ui-corner-all ui-state-default">'
                        +    '                    <span class="ui-icon ui-icon-circle-close">X</span>'
                        +    '                </a>'
                        +    '            </div>'
                        +    '            <div id="ptTimeSelect2UserTime" style="float: left;">'
                        +    '                <span id="ptTimeSelect2UserSelHr">1</span> : '
                        +    '                <span id="ptTimeSelect2UserSelMin">00</span> '
                        +    '                <span id="ptTimeSelect2UserSelAmPm">AM</span>'
                        +    '            </div>'
                        +    '            <br style="clear: both;" /><div></div>'
                        +    '        </div>'
                        +    '        <div class="ui-widget-content ui-corner-all">'
                        +    '            <div>'
                        +    '                <div class="ptTimeSelect2TimeLabelsCntr">'
                        +    '                    <div class="ptTimeSelect2LeftPane" style="width: 50%; text-align: center; float: left;" class="">Hour</div>'
                        +    '                    <div class="ptTimeSelect2RightPane" style="width: 50%; text-align: center; float: left;">Minutes</div>'
                        +    '                </div>'
                        +    '                <div>'
                        +    '                    <div style="float: left; width: 50%;">'
                        +    '                        <div class="ui-widget-content ptTimeSelect2LeftPane">'
                        +    '                            <div class="ptTimeSelect2HrAmPmCntr">'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);" '
                        +    '                                        style="display: block; width: 45%; float: left;">AM</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);" '
                        +    '                                        style="display: block; width: 45%; float: left;">PM</a>'
                        +    '                                <br style="clear: left;" /><div></div>'
                        +    '                            </div>'
                        +    '                            <div class="ptTimeSelect2HrCntr">'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">1</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">2</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">3</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">4</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">5</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">6</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">7</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">8</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">9</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">10</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">11</a>'
                        +    '                                <a class="ptTimeSelect2Hr ui-state-default" href="javascript: void(0);">12</a>'
                        +    '                                <br style="clear: left;" /><div></div>'
                        +    '                            </div>'
                        +    '                        </div>'
                        +    '                    </div>'
                        +    '                    <div style="width: 50%; float: left;">'
                        +    '                        <div class="ui-widget-content ptTimeSelect2RightPane">'
                        +    '                            <div class="ptTimeSelect2MinCntr">'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">00</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">05</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">10</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">15</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">20</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">25</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">30</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">35</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">40</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">45</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">50</a>'
                        +    '                                <a class="ptTimeSelect2Min ui-state-default" href="javascript: void(0);">55</a>'
                        +    '                                <br style="clear: left;" /><div></div>'
                        +    '                            </div>'
                        +    '                        </div>'
                        +    '                    </div>'
                        +    '                </div>'
                        +    '            </div>'
                        +    '            <div style="clear: left;"></div>'
                        +    '        </div>'
                        +    '        <div id="ptTimeSelect2SetButton">'
                        +    '            <a href="javascript: void(0);" onclick="jQuery.ptTimeSelect2.setTime()"'
                        +    '                    onmouseover="jQuery(this).removeClass(\'ui-state-default\').addClass(\'ui-state-hover\');" '
                        +    '                        onmouseout="jQuery(this).removeClass(\'ui-state-hover\').addClass(\'ui-state-default\');"'
                        +    '                        class="ui-corner-all ui-state-default">'
                        +    '                SET'
                        +    '            </a>'
                        +    '            <br style="clear: both;" /><div></div>'
                        +    '        </div>'
                        +    '        <!--[if lte IE 6.5]>'
                        +    '            <iframe style="display:block; position:absolute;top: 0;left:0;z-index:-1;'
                        +    '                filter:Alpha(Opacity=\'0\');width:3000px;height:3000px"></iframe>'
                        +    '        <![endif]-->'
                        +    '    </div></div>'
                    );
                    
                    var e = jQuery('#ptTimeSelect2Cntr');
    
                    // Add the events to the functions
                    e.find('.ptTimeSelect2Min')
                        .bind("click", function(){
                            jQuery.ptTimeSelect2.setMin($(this).text());
                         });
                    
                    e.find('.ptTimeSelect2Hr')
                        .bind("click", function(){
                            jQuery.ptTimeSelect2.setHr($(this).text());
                         });
                    
                    $(document).mousedown(jQuery.ptTimeSelect2._doCheckMouseClick);            
                }//end if
            }
        );
    }();// jQuery.ptTimeSelect2Init()
    
    
    /**
     * Sets the hour selected by the user on the popup.
     * 
     * @private 
     * @param  {Integer}   h   -   Interger indicating the hour. This value
     *                      is the same as the text value displayed on the
     *                      popup under the hour. This value can also be the
     *                      words AM or PM.
     * @return {undefined}
     * 
     */
    jQuery.ptTimeSelect2.setHr = function(h) {
        if (    h.toLowerCase() == "am"
            ||  h.toLowerCase() == "pm"
        ) {
            jQuery('#ptTimeSelect2UserSelAmPm').empty().append(h);
        } else {
            jQuery('#ptTimeSelect2UserSelHr').empty().append(h);
        }
    };// END setHr() function
        
    /**
     * Sets the minutes selected by the user on the popup.
     * 
     * @private
     * @param {Integer}    m   - interger indicating the minutes. This
     *          value is the same as the text value displayed on the popup
     *          under the minutes.
     * @return {undefined}
     */
    jQuery.ptTimeSelect2.setMin = function(m) {
        jQuery('#ptTimeSelect2UserSelMin').empty().append(m);
    };// END setMin() function
        
    /**
     * Takes the time defined by the user and sets it to the input
     * element that the popup is currently opened for.
     * 
     * @private
     * @return {undefined}
     */
    jQuery.ptTimeSelect2.setTime = function() {
        var tSel = jQuery('#ptTimeSelect2UserSelHr').text()
                    + ":"
                    + jQuery('#ptTimeSelect2UserSelMin').text()
                    + " "
                    + jQuery('#ptTimeSelect2UserSelAmPm').text();
        jQuery(".isptTimeSelect2Active").val(tSel);
        this.closeCntr();
        
    };// END setTime() function
        
    /**
     * Displays the time definition area on the page, right below
     * the input field.  Also sets the custom colors/css on the
     * displayed area to what ever the input element options were
     * set with.
     * 
     * @private
     * @param {String} uId - Id of the element for whom the area will
     *                  be displayed. This ID was created when the 
     *                  ptTimeSelect2() method was called.
     * @return {undefined}
     * 
     */
    jQuery.ptTimeSelect2.openCntr = function (ele) {
        jQuery.ptTimeSelect2.closeCntr();
        jQuery(".isptTimeSelect2Active").removeClass("isptTimeSelect2Active");
        var cntr            = jQuery("#ptTimeSelect2Cntr");
        var i               = jQuery(ele).eq(0).addClass("isptTimeSelect2Active");
        var opt             = i.data("ptTimeSelect2Options");
        var style           = i.offset();
        style['z-index']    = opt.zIndex;
        style.top           = (style.top + i.outerHeight());
        if (opt.containerWidth) {
            style.width = opt.containerWidth;
        }
        if (opt.containerClass) {
            cntr.addClass(opt.containerClass);
        }
        cntr.css(style);
        var hr    = 1;
        var min   = '00';
        var tm    = 'AM';
        if (i.val()) {
            var re = /([0-9]{1,2}).*:.*([0-9]{2}).*(PM|AM)/i;
            var match = re.exec(i.val());
            if (match) {
                hr    = match[1] || 1;
                min    = match[2] || '00';
                tm    = match[3] || 'AM';
            }
        }
        cntr.find("#ptTimeSelect2UserSelHr").empty().append(hr);
        cntr.find("#ptTimeSelect2UserSelMin").empty().append(min);
        cntr.find("#ptTimeSelect2UserSelAmPm").empty().append(tm);
        cntr.find(".ptTimeSelect2TimeLabelsCntr .ptTimeSelect2LeftPane")
            .empty().append(opt.hoursLabel);
        cntr.find(".ptTimeSelect2TimeLabelsCntr .ptTimeSelect2RightPane")
            .empty().append(opt.minutesLabel);
        cntr.find("#ptTimeSelect2SetButton a").empty().append(opt.setButtonLabel);
        if (opt.onBeforeShow) {
            opt.onBeforeShow(i, cntr);
        }
        cntr.slideDown("fast");
            
    };// END openCntr()
        
    /**
     * Closes (hides it) the popup container.
     * @private
     * @param {Object} i    -   Optional. The input field for which the
     *                          container is being closed.
     * @return {undefined}
     */
    jQuery.ptTimeSelect2.closeCntr = function(i) {
        var e = $("#ptTimeSelect2Cntr");
        if (e.is(":visible") == true) {
            
            // If IE, then check to make sure it is realy visible
            if (jQuery.support.tbody == false) {
                if (!(e[0].offsetWidth > 0) && !(e[0].offsetHeight > 0) ) {
                    return;
                }
            }
            
            jQuery('#ptTimeSelect2Cntr')
                .css("display", "none")
                .removeClass()
                .css("width", "");
            if (!i) {
                i = $(".isptTimeSelect2Active");
            }
            if (i) {
                var opt = i.removeClass("isptTimeSelect2Active")
                            .data("ptTimeSelect2Options");
                if (opt && opt.onClose) {
                    opt.onClose(i);
                }
            }
        }
        return;
    };//end closeCntr()
    
    /**
     * Closes the timePicker popup if user is not longer focused on the
     * input field or the timepicker
     * 
     * @private
     * @param {jQueryEvent} ev -    Event passed in by jQuery
     * @return {undefined}
     */
    jQuery.ptTimeSelect2._doCheckMouseClick = function(ev){
        if (!$("#ptTimeSelect2Cntr:visible").length) {
            return;
        }
        if (   !jQuery(ev.target).closest("#ptTimeSelect2Cntr").length
            && jQuery(ev.target).not("input.isptTimeSelect2Active").length ){
            jQuery.ptTimeSelect2.closeCntr();
        }
        
    };// jQuery.ptTimeSelect2._doCheckMouseClick
    
    /**
     * FUNCTION: $().ptTimeSelect2()
     * Attaches a ptTimeSelect2 widget to each matched element. Matched
     * elements must be input fields that accept a values (input field).
     * Each element, when focused upon, will display a time selection 
     * popoup where the user can define a time.
     * 
     * @memberOf jQuery
     * 
     * PARAMS:
     * 
     * @param {Object}      [opt] - An object with the options for the time selection widget.
     * 
     * @param {String}      [opt.containerClass=""] - A class to be associated with the popup widget.
     * 
     * @param {String}      [opt.containerWidth=""] - Css width for the container.
     * 
     * @param {String}      [opt.hoursLabel="Hours"] - Label for the Hours.
     * 
     * @param {String}      [opt.minutesLabel="Minutes"] - Label for the Mintues container.
     * 
     * @param {String}      [opt.setButtonLabel="Set"] - Label for the Set button.
     * 
     * @param {String}      [opt.popupImage=""] - The html element (ex. img or text) to be appended next to each
     *      input field and that will display the time select widget upon
     *      click.
     * 
     * @param {Integer}     [opt.zIndex=10] - Integer for the popup widget z-index.
     * 
     * @param {Function}    [opt.onBeforeShow=undefined] - Function to be called before the widget is made visible to the 
     *      user. Function is passed 2 arguments: 1) the input field as a 
     *      jquery object and 2) the popup widget as a jquery object.
     * 
     * @param {Function}    [opt.onClose=undefined] - Function to be called after closing the popup widget. Function
     *      is passed 1 argument: the input field as a jquery object.
     * 
     * @param {Bollean}     [opt.onFocusDisplay=true] - True or False indicating if popup is auto displayed upon focus
     *      of the input field.
     * 
     * 
     * RETURN:
     * @return {jQuery} selection
     * 
     * 
     * 
     * EXAMPLE:
     * @example
     *  $('#fooTime').ptTimeSelect2();
     * 
     */
    jQuery.fn.ptTimeSelect2 = function (opt) {
        return this.each(function(){
            if(this.nodeName.toLowerCase() != 'input') return;
            var e = jQuery(this);
            if (e.hasClass('hasptTimeSelect2')){
                return this;
            }
            var thisOpt = {};
            thisOpt = $.extend(thisOpt, jQuery.ptTimeSelect2.options, opt);
            e.addClass('hasptTimeSelect2').data("ptTimeSelect2Options", thisOpt);
            
            //Wrap the input field in a <div> element with
            // a unique id for later referencing.
            if (thisOpt.popupImage || !thisOpt.onFocusDisplay) {
                var img = jQuery('<span>&nbsp;</span><a href="javascript:" onclick="' +
                        'jQuery.ptTimeSelect2.openCntr(jQuery(this).data(\'ptTimeSelect2Ele\'));">' +
                        thisOpt.popupImage + '</a>'
                    )
                    .data("ptTimeSelect2Ele", e);
                e.after(img);
            }
            if (thisOpt.onFocusDisplay){
                e.focus(function(){
                    jQuery.ptTimeSelect2.openCntr(this);
                });
            }
            return this;
        });
    };// End of jQuery.fn.ptTimeSelect2
    
})(jQuery);
