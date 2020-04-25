const _ = require('lodash');

var config = {};

config.twitter = {};
config.redis = {};
config.web = {};
config.d8 = {};

config.default_stuff =  ['red','green','blue','apple','yellow','orange','politics'];
config.twitter.user_name = process.env.TWITTER_USER || 'username';
config.twitter.password=  process.env.TWITTER_PASSWORD || 'password';
config.redis.uri = process.env.DUOSTACK_DB_REDIS;
config.redis.host = 'hostname';
config.redis.port = 6379;
config.web.port = process.env.WEB_PORT || 9980;

config.d8.host              = 'http://drupal8';
config.d8.api_login         = config.d8.host + '/api/login.json';
config.d8.api_register      = config.d8.host + '/api/register.json';
config.d8.api_reset_password= config.d8.host + '/api/reset_password.json';

config.d8.api_list_bank     = config.d8.host + '/api/list_bank.json';
config.d8.api_add_bank      = config.d8.host + '/api/add_bank.json';
config.d8.api_delete_bank   = config.d8.host + '/api/delete_bank.json';

config.d8.headers = {
                        "Content-Type": "application/json",
                        // "client_id": "1001125",
                        // "client_secret": "876JHG76UKFJYGVHf867rFUTFGHCJ8JHV"
                    };

module.exports = config;