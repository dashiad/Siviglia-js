location ~ /js/dojo/adtopy/
{
try_files $uri /admin/translator.php?subpath=$uri&$args;
}
location ~ /js/dojo/web/
{
try_files $uri /admin/translator.php?subpath=$uri&$args;
}

# Inicio de cambios para version jquery
location ~ /js/adtopy/
{
root /var/www/adtopy/model/adtopy/objects/;
rewrite /js/adtopy/([^/]*)/objects/([^/]*)/(.*)\.js$  /$1/objects/$2/js/$3.js break;
rewrite /js/adtopy/([^/]*)/(.*)\.js$  /$1/js/$2.js break;
}

location ~ /js/jquery/adtopy/
{
root /var/www/adtopy/model/adtopy/objects/;
rewrite /js/jquery/adtopy/([^/]*)/objects/([^/]*)/(.*)\.js$  /$1/objects/$2/js/jquery/$3.js break;
rewrite /js/jquery/adtopy/([^/]*)/(.*)\.js$  /$1/js/jquery/$2.js break;
rewrite /js/jquery/adtopy/([^/]*)/objects/([^/]*)/(.*)\.html$  /$1/objects/$2/js/jquery/$3.html break;
rewrite /js/jquery/adtopy/([^/]*)/(.*)\.html$  /$1/js/$2.html break;
}

#GZIP config_start
gzip  on;
gzip_static on;
gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript text/x-js;

# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
#
location ~ \.php$ {
proxy_connect_timeout  1800s;
proxy_send_timeout  1800s;
proxy_read_timeout  1800s;
fastcgi_send_timeout 1800s;
fastcgi_read_timeout 1800s;
fastcgi_param PHP_VALUE "include_path=/var/www/adtopy/install/config:.";
fastcgi_buffers 16 16k;
fastcgi_buffer_size 32k;
fastcgi_split_path_info ^(.+\.php)(/.+)$;
#       # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini
#       # With php5-cgi alone:
#       fastcgi_pass 127.0.0.1:9000;
#       # With php5-fpm:
fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
fastcgi_keep_conn on;
fastcgi_index index.php;
fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
include fastcgi_params;
}

location ~ /\. {
deny all;
}

# Deny access to .htaccess files,
# git & svn repositories, etc
location ~ /(\.ht|\.git|\.svn) {
deny  all;
}
