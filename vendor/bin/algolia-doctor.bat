@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../algolia/algoliasearch-client-php/bin/algolia-doctor
php "%BIN_TARGET%" %*
