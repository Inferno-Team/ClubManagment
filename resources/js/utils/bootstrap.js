window._ = require('lodash');
import {
    CONSTANCES
} from './utils'
try {
    require('bootstrap');
} catch (e) {}


window.axios = require('axios');
let token = localStorage.getItem(CONSTANCES.TOKEN_NAME);

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
if (token != null && token != undefined)
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
