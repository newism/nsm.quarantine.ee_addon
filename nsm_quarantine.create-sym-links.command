#!/bin/bash

# This script creates symlinks from the local GIT repo into your EE install. It also copies some of the extension icons.

dirname=`dirname "$0"`

echo "

Creating symlinks for NSM Quarantine
------------------------------------

The symlinks use absolute paths so they are for development purposes only.

The following directories must be writable:

system/extensions
system/lib
system/language/*
system/modules
themes/cp_global_images
themes/cp_themes/default
themes/site_themes/default

Enter the full path to your ExpressionEngine install *without a trailing slash* [ENTER]:"
read ee_path
echo "
Enter your system folder name [ENTER]:"
read ee_system_folder

# Extensions
ln -s -f "$dirname"/system/extensions/ext.nsm_quarantine_ext.php "$ee_path"/"$ee_system_folder"/extensions/ext.nsm_quarantine_ext.php

#lib
ln -s -f "$dirname"/system/lib/nsm_quarantine "$ee_path"/"$ee_system_folder"/lib

# Language
ln -s -f "$dirname"/system/language/english/lang.nsm_quarantine_ext.php "$ee_path"/"$ee_system_folder"/language/english/lang.nsm_quarantine_ext.php
ln -s -f "$dirname"/system/language/english/lang.nsm_quarantine.php "$ee_path"/"$ee_system_folder"/language/english/lang.nsm_quarantine.php

# Modules
ln -s -f "$dirname"/system/modules/nsm_quarantine "$ee_path"/"$ee_system_folder"/modules

# Themes
ln -s -f "$dirname"/themes/cp_themes/default/nsm_quarantine "$ee_path"/themes/cp_themes/default
ln -s -f "$dirname"/themes/site_themes/default/nsm_quarantine "$ee_path"/themes/site_themes/default
cp "$dirname"/themes/cp_global_images/ "$ee_path"/themes/cp_global_images