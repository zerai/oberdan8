/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import './styles/app.scss';

import './styles/backoffice.scss';

// require jQuery normally
//const $ = require('jquery');
// create global $ and jQuery variables
//global.$ = global.jQuery = $;




import $ from 'jquery';
import 'bootstrap';
import 'popper.js';

import bsCustomFileInput from 'bs-custom-file-input';





// start the Stimulus application
import './bootstrap';

bsCustomFileInput.init();

// $(document).ready(function(){
//     console.log(' bottom backoffice in backoffice.js ok');
// })