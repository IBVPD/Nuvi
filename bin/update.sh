#!/bin/sh

if [[ $EUID -eq 0 ]]; then
   echo "This script must not be run as root probably apache 'su - apache -s /bin/sh' " 1>&2
   exit 1
fi

export http_proxy=http://proxy.who.int:3128
export https_proxy=http://proxy.who.int:3128

command=$1

function update() {
    SYMFONY_ENV=prod composer install -o
    bin/console doctrine:migrations:migrate --env=prod
}

function getLatest() {
#    echo "Running: git pull > /dev/null 2>&1";
    git pull > /dev/null 2>&1 || { echo >&2 "Unable to pull latest version"; exit 1; }
    update
}

function getLatestRelease() {
    TAG=`git tag -l | sort -V | tail -n1`
    echo "UPDATING TO RELEASE $TAG";
#    echo "Running: git checkout $TAG";
    git checkout $TAG
    update
}

if [[ -n "$command" ]]; then
#   echo "Running: git fetch > /dev/null 2>&1";
   git fetch > /dev/null 2>&1 || { echo >&2 "git is required.  Aborting."; exit 1; }
#   echo "Running: git stash";

   git diff --quiet
   NEEDSTASH=$?

   if [ $NEEDSTASH -eq 1 ]; then
      git stash
   fi

   case "$command" in
     'latest')
        getLatest
        ;;
     'latest-release')
        getLatestRelease
        ;;
     *)
        echo 'unknown command';
        ;;
   esac
#   echo "Running: git stash pop";

   if [ $NEEDSTASH -eq 1 ]; then
      git stash pop
   fi
else
    echo "Missing arguments [latest|latest-release]";
fi

