/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios'
import Example from './components/Example'
import Searchbar from './components/Searchbar'

/**
* Create a fresh Vue Application instance
*/

Vue.use(VueAxios, axios)
Vue.prototype.$axios = axios

new Vue({
    el: '#app',
    components: { Example, 
            Searchbar}
});

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
