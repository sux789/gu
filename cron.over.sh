cd `dirname $0`
# echo  "\n`date +$m%d%H%i%s``pwd`"
php artisan cron >> ./cron.over.log
