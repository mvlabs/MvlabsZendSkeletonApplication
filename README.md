ZendSkeletonApplication
=======================

Introduction
------------
This is a simple, skeleton application using the ZF2 MVC layer and module
systems. This application is meant to be used as a starting template for building ZF2 applications.


Where does this differ from standard Zend Skeleton Application?
----------------------------------------------------------------------------
Something we have been missing on std ZF2 Skeleton App is native application handling. We detect application running environment at runtime, so to speed up development and deployment.
This application takes current environment into account and handles the following accordingly, through configuration files:

1) Error reporting settings
2) Error handling settings
3) PHP ini settings
4) Fatal errors graceful degradation
5) Custom module loading (IE developer toolbar on dev environment only)


Not only it's now easy to load development modules depending on currrent environment, or setting different php.ini params at application startup, but it's also possible to use different views on error pages, depending on current context. This is b/c we think that end users do not care about seeing exception details (and would be much less frustrated if looking at a full screen funny image), whereas we want developers to see cleanest error for quicker handling of such occurrencies. 

Additionally environment name is also passed to the view component, so to allow for quick view customization (IE through CSS) depending on current running environment.


Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use `composer` to install dependencies using the `create-project` command:

    curl -s https://getcomposer.org/installer | php --
    php composer.phar create-project -sdev --repository-url="http://packages.mvlabs.it" mvlabs/zf2-skeleton-application path/to/install

Alternately, clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/mvlabs/MvlabsZendSkeletonApplication.git
    cd MvlabsZendSkeletonApplication
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Another alternative for downloading the project is to grab it via `curl`, and
then pass it to `tar`:

    cd my/project/dir
    curl -#L https://github.com/mvlabs/MvlabsZendSkeletonApplication/tarball/master | tar xz --strip-components=1

You would then invoke `composer` to install dependencies per the previous
example.

Using Git submodules
--------------------
Alternatively, you can install using native git submodules:

    git clone git://github.com/mvlabs/MvlabsZendSkeletonApplication.git --recursive

Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!
