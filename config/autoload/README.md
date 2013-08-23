About this directory:
=====================

By default, the original ZendSkeletonApplication is configured to load all configs in
`./config/autoload/{,*.}{global,local}.php`. 

Doing this provides a location for a developer to drop in configuration override files provided by modules, as well as cleanly provide individual, application-wide config files
for things like database connections, etc.

Additionally, the MV Labs version of SkeletonApplication allows to have `./config/autoload/{,*.}ENV.php` files, with ENV being current execution environment. This is useful if you have specific application settings for specific environments, which do not contain sensitive data and which can - therefore - safely be committed to a source control system.
