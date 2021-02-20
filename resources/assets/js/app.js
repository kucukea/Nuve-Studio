
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });


var callInitLang = function() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        /* the route pointing to the post function */
        url: "/initLanguage",
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: { _token: CSRF_TOKEN },
        dataType: 'JSON',
        timeout: 900000,
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) {
            if (data.status) {
                $('.WhenNotLangReady').fadeOut('slow', function () {
                    $('.WhenLangReady').css('display', 'flex').fadeIn('slow');
                });
            }
            else {
                alert("No Lang!!");
            }
        },
        error: function (q, e, d) {
            // console.log(q);
            // console.log(e);
            console.log(d);
            callInitLang();
        }
    });
}

window.callInitLang = callInitLang;

var callOrthographyResult = function (strText, inflection, calbackWhenDone) {
    if (strText == ""){
        alert("Please fill the text!!");
        return;
    }
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        /* the route pointing to the post function */
        url: "/analyze",
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: { _token: CSRF_TOKEN, _text: strText, inflection: inflection },
        dataType: 'JSON',
        timeout: 900000,
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) {
            calbackWhenDone(data);
        },
        error: function (q, e, d) {
            console.log(d);
            callOrthographyResult(strText, inflection, calbackWhenDone);
        }
    });
}

window.callOrthographyResult = callOrthographyResult;

var callMorphotacticsResult = function (strText, calbackWhenDone) {
    if (strText == "") {
        alert("Please fill the text!!");
        return;
    }
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        /* the route pointing to the post function */
        url: "/generate",
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: { _token: CSRF_TOKEN, _text: strText },
        dataType: 'JSON',
        timeout: 900000,
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) {
            calbackWhenDone(data);
        },
        error: function (q, e, d) {
            console.log(d);
            callMorphotacticsResult(strText, calbackWhenDone);
        }
    });
}

window.callMorphotacticsResult = callMorphotacticsResult;