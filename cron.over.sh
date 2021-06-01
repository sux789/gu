cd `dirname $0`
echo "当前执行`pwd`"
php artisan cron >> ./cron.over.log
